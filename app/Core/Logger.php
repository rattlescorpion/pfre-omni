<?php declare(strict_types=1);

namespace App\Core;

use Illuminate\Support\Facades\Log;

/**
 * This class handles recording errors or important 
 * business events to a file.
 */
class Logger
{
    public function info(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        Log::error($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        Log::warning($message, $context);
    }
}