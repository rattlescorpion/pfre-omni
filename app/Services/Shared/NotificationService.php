<?php declare(strict_types=1);

namespace App\Services\Shared;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Sends an in-app notification and records it in the database.
     */
    public function send(
        int $userId, 
        string $type, 
        string $title, 
        string $body, 
        string $icon = 'bell', 
        string $color = 'blue'
    ): void {
        DB::table('notifications')->insert([
            'uuid'       => Str::uuid()->toString(),
            'user_id'    => $userId,
            'type'       => $type,
            'title'      => $title,
            'body'       => $body,
            'icon'       => $icon,
            'color'      => $color,
            'is_read'    => 0,
            'created_at' => now(),
        ]);

        // In the future, we would add code here to send 
        // real WhatsApp or Email alerts!
    }
}