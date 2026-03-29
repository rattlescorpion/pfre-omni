<?php declare(strict_types=1);

namespace App\Services\Shared;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Stores a value in the cache for a specific amount of time.
     */
    public function remember(string $key, int $seconds, \Closure $callback)
    {
        return Cache::remember($key, $seconds, $callback);
    }

    /**
     * Removes an item from the cache.
     */
    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * Clears a group of cached items (tags).
     */
    public function invalidateGroup(string $tag): void
    {
        // For simple file caching, we just clear the key. 
        // In high-end systems, this would use Redis tags.
        Cache::forget($tag);
    }
}