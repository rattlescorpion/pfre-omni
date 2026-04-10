<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'api_key',
        'secret',
        'token',
        'credit_card',
        'ssn',
        'pan_number',
        'aadhaar_number',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log security-related exceptions
            if ($e instanceof \Illuminate\Auth\AuthenticationException ||
                $e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                \Log::warning('Security exception', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'url' => request()->fullUrl(),
                ]);
            }
        });

        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'error' => 'authentication_required'
                ], 401);
            }
        });

        $this->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'This action is unauthorized.',
                    'error' => 'insufficient_permissions'
                ], 403);
            }
        });
    }
}
