<?php declare(strict_types=1);

namespace App\Services\Shared;

use Illuminate\Support\Facades\Log;

class EnvironmentValidator
{
    /**
     * Validate critical environment variables for security.
     *
     * @return array
     */
    public static function validate(): array
    {
        $issues = [];
        $warnings = [];

        // Check for APP_KEY
        if (empty(config('app.key')) || config('app.key') === 'base64:YourAppKeyHere') {
            $issues[] = 'APP_KEY is not set or using default value';
        }

        // Check for database credentials
        if (empty(config('database.connections.mysql.password'))) {
            $issues[] = 'Database password is not set';
        }

        // Check for debug mode in production
        if (config('app.debug') && config('app.env') === 'production') {
            $issues[] = 'Debug mode is enabled in production';
        }

        // Check for secure session settings
        if (!config('session.encrypt')) {
            $issues[] = 'Session encryption is disabled';
        }

        // Check for CORS security
        if (config('cors.allowed_origins') === ['*']) {
            $issues[] = 'CORS allows all origins - consider restricting';
        }

        // Check for mail encryption
        if (config('mail.encryption') !== 'tls' && config('mail.encryption') !== 'ssl') {
            $warnings[] = 'Mail encryption is not configured properly';
        }

        // Check for backup encryption
        if (!config('backup.password')) {
            $warnings[] = 'Database backup password is not set';
        }

        // Log issues
        if (!empty($issues)) {
            Log::warning('Environment Security Issues Detected', [
                'issues' => $issues,
                'environment' => config('app.env'),
                'timestamp' => now(),
            ]);
        }

        if (!empty($warnings)) {
            Log::info('Environment Security Warnings Detected', [
                'warnings' => $warnings,
                'environment' => config('app.env'),
                'timestamp' => now(),
            ]);
        }

        if (empty($issues) && empty($warnings)) {
            Log::info('Environment security validation passed');
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'warnings' => $warnings,
        ];
    }

    /**
     * Validate that sensitive files are not accessible.
     *
     * @return array
     */
    public static function checkFilePermissions(): array
    {
        $sensitiveFiles = [
            '.env',
            '.env.example',
            'storage/logs/laravel.log',
            'storage/app/private',
        ];

        $issues = [];
        $warnings = [];

        foreach ($sensitiveFiles as $file) {
            $path = base_path($file);
            if (file_exists($path) && is_readable($path)) {
                // Check if file is accessible via web
                $webPath = str_replace(base_path(), '', $path);
                if (str_starts_with($webPath, '/storage') && !str_contains($webPath, 'private')) {
                    $warnings[] = "File {$file} might be web-accessible";
                }
            }
        }

        if (!empty($warnings)) {
            Log::warning('File Permission Issues Detected', [
                'warnings' => $warnings,
                'timestamp' => now(),
            ]);
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'warnings' => $warnings,
        ];
    }
}