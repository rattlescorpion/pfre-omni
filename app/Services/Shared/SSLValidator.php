<?php

namespace App\Services\Shared;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class SSLValidator
{
    /**
     * Validate SSL/TLS configuration
     *
     * @return array
     */
    public static function validate(): array
    {
        $issues = [];
        $warnings = [];

        // Check if HTTPS is enforced
        if (!Request::secure() && !app()->environment('local')) {
            $issues[] = 'HTTPS is not being used. SSL/TLS should be enforced in production.';
        }

        // Check SSL certificate validity (if HTTPS is enabled)
        if (Request::secure()) {
            $certificateInfo = self::getCertificateInfo();

            if ($certificateInfo) {
                // Check certificate expiration
                $expirationDate = strtotime($certificateInfo['validTo_time_t']);
                $daysUntilExpiration = ($expirationDate - time()) / (60 * 60 * 24);

                if ($daysUntilExpiration < 30) {
                    $issues[] = "SSL certificate expires in {$daysUntilExpiration} days. Renew immediately.";
                } elseif ($daysUntilExpiration < 90) {
                    $warnings[] = "SSL certificate expires in {$daysUntilExpiration} days. Plan renewal.";
                }

                // Check certificate issuer
                if (isset($certificateInfo['issuer']['CN'])) {
                    $issuer = $certificateInfo['issuer']['CN'];
                    if (stripos($issuer, 'self-signed') !== false) {
                        $issues[] = 'Self-signed SSL certificate detected. Use a trusted CA certificate in production.';
                    }
                }

                // Check certificate strength
                if (isset($certificateInfo['bits'])) {
                    $keySize = $certificateInfo['bits'];
                    if ($keySize < 2048) {
                        $issues[] = "SSL certificate key size is {$keySize} bits. Minimum recommended is 2048 bits.";
                    }
                }
            } else {
                $warnings[] = 'Unable to retrieve SSL certificate information.';
            }
        }

        // Check HSTS header
        if (!Request::header('Strict-Transport-Security') && Request::secure()) {
            $warnings[] = 'HTTP Strict Transport Security (HSTS) header not set.';
        }

        // Check for mixed content
        if (Request::secure()) {
            $mixedContent = self::checkMixedContent();
            if ($mixedContent) {
                $issues[] = 'Mixed content detected: HTTP resources loaded over HTTPS.';
            }
        }

        // Log issues and warnings
        if (!empty($issues)) {
            Log::warning('SSL/TLS Security Issues:', $issues);
        }

        if (!empty($warnings)) {
            Log::info('SSL/TLS Security Warnings:', $warnings);
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'warnings' => $warnings,
        ];
    }

    /**
     * Get SSL certificate information
     *
     * @return array|null
     */
    private static function getCertificateInfo(): ?array
    {
        $host = Request::getHost();

        // Skip for localhost/development
        if (in_array($host, ['localhost', '127.0.0.1', '::1']) || app()->environment('local')) {
            return null;
        }

        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $socket = @stream_socket_client(
            "ssl://{$host}:443",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$socket) {
            return null;
        }

        $params = stream_context_get_params($socket);
        $certificate = $params['options']['ssl']['peer_certificate'] ?? null;

        if (!$certificate) {
            fclose($socket);
            return null;
        }

        $certInfo = openssl_x509_parse($certificate);
        fclose($socket);

        return $certInfo;
    }

    /**
     * Check for mixed content on the current page
     *
     * @return bool
     */
    private static function checkMixedContent(): bool
    {
        // This is a simplified check - in a real implementation,
        // you might want to parse the HTML content for HTTP URLs
        // For now, we'll check if any HTTP requests were made to this host
        return false; // Placeholder - would need more complex implementation
    }

    /**
     * Force HTTPS redirect if SSL is required
     *
     * @return void
     */
    public static function enforceSSL(): void
    {
        if (!Request::secure() && !app()->environment('local') && env('FORCE_HTTPS', false)) {
            $url = 'https://' . Request::getHost() . Request::getRequestUri();
            header("Location: {$url}", true, 301);
            exit;
        }
    }

    /**
     * Get SSL security score
     *
     * @return int
     */
    public static function getSecurityScore(): int
    {
        $validation = self::validate();
        $score = 100;

        // Deduct points for issues
        $score -= count($validation['issues']) * 25;

        // Deduct points for warnings
        $score -= count($validation['warnings']) * 10;

        return max(0, $score);
    }
}