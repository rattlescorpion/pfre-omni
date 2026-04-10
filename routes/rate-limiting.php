<?php declare(strict_types=1);

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('api', function ($request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
        ->response(function ($request, $headers) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $headers['Retry-After'] ?? 60,
            ], 429, $headers);
        });
});

RateLimiter::for('uploads', function ($request) {
    return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip())
        ->response(function ($request, $headers) {
            return response()->json([
                'message' => 'Upload rate limit exceeded. Please try again later.',
                'retry_after' => $headers['Retry-After'] ?? 60,
            ], 429, $headers);
        });
});

RateLimiter::for('auth', function ($request) {
    return Limit::perMinute(5)->by($request->ip())
        ->response(function ($request, $headers) {
            return response()->json([
                'message' => 'Too many authentication attempts. Please try again later.',
                'retry_after' => $headers['Retry-After'] ?? 60,
            ], 429, $headers);
        });
});

RateLimiter::for('critical', function ($request) {
    return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip())
        ->response(function ($request, $headers) {
            return response()->json([
                'message' => 'Critical operation rate limit exceeded.',
                'retry_after' => $headers['Retry-After'] ?? 60,
            ], 429, $headers);
        });
});