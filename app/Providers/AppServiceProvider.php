<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Shared\QueryLogger;
use App\Services\Shared\EnvironmentValidator;
use App\Services\Shared\SSLValidator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enable database query logging for security monitoring
        if (config('app.debug') || config('app.env') === 'local') {
            QueryLogger::enable();
        }

        // Validate environment security settings
        $envValidation = EnvironmentValidator::validate();
        $fileValidation = EnvironmentValidator::checkFilePermissions();

        // In production, throw exception for critical issues
        if (config('app.env') === 'production' && (!$envValidation['valid'] || !empty($fileValidation['issues']))) {
            $criticalIssues = array_merge($envValidation['issues'], $fileValidation['issues']);
            throw new \RuntimeException('Critical security issues detected: ' . implode(', ', $criticalIssues));
        }

        // Validate SSL/TLS configuration
        SSLValidator::validate();
    }
}
