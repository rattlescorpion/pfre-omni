<?php declare(strict_types=1);

namespace App\Services\Shared;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class FileUploadValidator
{
    /**
     * Maximum file size in bytes (10MB default).
     */
    private const MAX_FILE_SIZE = 10 * 1024 * 1024;

    /**
     * Allowed MIME types.
     */
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'text/plain',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    /**
     * Dangerous file extensions to block.
     */
    private const DANGEROUS_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml',
        'exe', 'bat', 'cmd', 'com', 'scr', 'pif', 'vbs', 'js',
        'jar', 'war', 'ear', 'zip', 'rar', '7z', 'tar', 'gz',
    ];

    /**
     * Validate uploaded file for security.
     */
    public static function validate(UploadedFile $file, array $additionalAllowedTypes = []): bool
    {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            Log::warning('File upload rejected: File too large', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'max_size' => self::MAX_FILE_SIZE,
                'ip' => request()->ip(),
                'user_id' => auth()->id(),
            ]);
            return false;
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        $allowedTypes = array_merge(self::ALLOWED_MIME_TYPES, $additionalAllowedTypes);

        if (!in_array($mimeType, $allowedTypes)) {
            Log::warning('File upload rejected: Invalid MIME type', [
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $mimeType,
                'allowed_types' => $allowedTypes,
                'ip' => request()->ip(),
                'user_id' => auth()->id(),
            ]);
            return false;
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, self::DANGEROUS_EXTENSIONS)) {
            Log::alert('File upload rejected: Dangerous file extension', [
                'filename' => $file->getClientOriginalName(),
                'extension' => $extension,
                'ip' => request()->ip(),
                'user_id' => auth()->id(),
            ]);
            return false;
        }

        // Check for null bytes in filename
        if (str_contains($file->getClientOriginalName(), "\0")) {
            Log::alert('File upload rejected: Null bytes in filename', [
                'filename' => $file->getClientOriginalName(),
                'ip' => request()->ip(),
                'user_id' => auth()->id(),
            ]);
            return false;
        }

        // Check for suspicious patterns in filename
        $suspiciousPatterns = ['../', '..\\', '<script', 'javascript:', 'data:'];
        $filename = $file->getClientOriginalName();

        foreach ($suspiciousPatterns as $pattern) {
            if (str_contains(strtolower($filename), $pattern)) {
                Log::alert('File upload rejected: Suspicious filename pattern', [
                    'filename' => $filename,
                    'pattern' => $pattern,
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                ]);
                return false;
            }
        }

        return true;
    }

    /**
     * Generate a secure filename.
     */
    public static function generateSecureFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $hash = hash('sha256', $file->get() . microtime() . rand());
        return substr($hash, 0, 32) . '.' . $extension;
    }

    /**
     * Validate file upload configuration for security.
     *
     * @return array
     */
    public static function validateConfiguration(): array
    {
        $issues = [];
        $warnings = [];

        // Check if file uploads are properly configured
        $uploadConfig = config('filesystems.disks');

        if (!isset($uploadConfig['public'])) {
            $warnings[] = 'Public disk not configured for file uploads';
        }

        if (!isset($uploadConfig['private'])) {
            $warnings[] = 'Private disk not configured for sensitive files';
        }

        // Check upload limits in php.ini (if accessible)
        $uploadMaxSize = ini_get('upload_max_filesize');
        $postMaxSize = ini_get('post_max_size');

        if ($uploadMaxSize && self::convertToBytes($uploadMaxSize) > self::MAX_FILE_SIZE) {
            $warnings[] = 'PHP upload_max_filesize is larger than application limit - consider aligning';
        }

        if ($postMaxSize && self::convertToBytes($postMaxSize) < self::convertToBytes($uploadMaxSize)) {
            $issues[] = 'PHP post_max_size is smaller than upload_max_filesize - file uploads will fail';
        }

        // Check if dangerous file types could be uploaded
        $allowedTypes = self::ALLOWED_MIME_TYPES;
        $dangerousTypes = ['application/x-php', 'application/x-httpd-php', 'text/x-php'];

        foreach ($dangerousTypes as $type) {
            if (in_array($type, $allowedTypes)) {
                $issues[] = "Dangerous MIME type '{$type}' is allowed for upload";
            }
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'warnings' => $warnings,
        ];
    }

    /**
     * Convert PHP size notation to bytes.
     *
     * @param string $size
     * @return int
     */
    private static function convertToBytes(string $size): int
    {
        $size = trim($size);
        $last = strtolower($size[strlen($size) - 1]);
        $size = (int) $size;

        switch ($last) {
            case 'g':
                $size *= 1024 * 1024 * 1024;
                break;
            case 'm':
                $size *= 1024 * 1024;
                break;
            case 'k':
                $size *= 1024;
                break;
        }

        return $size;
    }
}