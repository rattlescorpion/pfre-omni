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
            
            // Log the request for security auditing
            $this->audit->log(
                action: 'http_request',
                entityType: 'http_request',
                entityId: uniqid('req_', true),
                oldValues: [],
                newValues: [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status_code' => $response->getStatusCode(),
                    'user_id' => auth()->id(),
                ],
                userId: auth()->id()
            );
            
            return $response;
        }
        
        return $next($request);
    }
}