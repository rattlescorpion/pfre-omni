<?php declare(strict_types=1);

namespace App\Services\Shared;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLogger
{
    public static function enable(): void
    {
        DB::listen(function ($query) {
            // Log slow queries (>100ms)
            if ($query->time > 100) {
                Log::warning('Slow Query Detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms',
                    'connection' => $query->connectionName,
                ]);
            }

            // Log potentially dangerous queries
            $sql = strtolower($query->sql);
            if (str_contains($sql, 'drop') ||
                str_contains($sql, 'truncate') ||
                str_contains($sql, 'alter table') ||
                str_contains($sql, 'create table')) {
                Log::info('DDL Query Executed', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms',
                    'user_id' => auth()->id(),
                    'ip' => request()->ip(),
                ]);
            }

            // Log queries with suspicious patterns (potential injection attempts)
            $suspiciousPatterns = [';', 'union', 'select.*from.*information_schema', 'benchmark', 'sleep'];
            foreach ($suspiciousPatterns as $pattern) {
                if (str_contains($sql, $pattern)) {
                    Log::alert('Suspicious Query Pattern Detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'pattern' => $pattern,
                        'user_id' => auth()->id(),
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);
                    break;
                }
            }
        });
    }
}