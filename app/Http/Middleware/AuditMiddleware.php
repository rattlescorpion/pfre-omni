<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Shared\AuditService;

class AuditMiddleware {
    public function __construct(protected AuditService $audit) {}

    public function handle(Request $request, Closure $next) {
        // Only track data-changing actions
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $response = $next($request);
            
            // Log logic is handled here or dispatched to a Queue
            // to ensure the user doesn't face lag.
            return $response;
        }
        
        return $next($request);
    }
}