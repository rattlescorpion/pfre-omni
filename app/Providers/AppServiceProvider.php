<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
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
        // 1. Force HTTPS in Production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // 2. Configure Rate Limiters for PFRE-Omni API
        $this->configureRateLimiting();

        // 3. Enable database query logging for local debugging/monitoring
        if (config('app.debug') || config('app.env') === 'local') {
            QueryLogger::enable();
        }

        // 4. Validate Environment Security Settings
        $envValidation = EnvironmentValidator::validate();
        $fileValidation = EnvironmentValidator::checkFilePermissions();

        // In production, enforce zero-tolerance for critical security flaws
        if (config('app.env') === 'production' && (!$envValidation['valid'] || !empty($fileValidation['issues']))) {
            $criticalIssues = array_merge($envValidation['issues'], $fileValidation['issues'] ?? []);
            throw new \RuntimeException('Critical security issues detected on PFRE-Omni: ' . implode(', ', $criticalIssues));
        }

        // 5. Validate SSL/TLS configuration
        SSLValidator::validate();
    }

    /**
     * Configure the rate limiters for the application.
     * Matches the throttle names used in routes/api.php
     */
    protected function configureRateLimiting(): void
    {
        // General API access (e.g., property search)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Authentication routes (login/register) - Strict protection
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Critical operations (eRegistration "33-field Master" submission)
        RateLimiter::for('critical', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
    }
}