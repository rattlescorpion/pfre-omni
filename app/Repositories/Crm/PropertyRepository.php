<?php declare(strict_types=1);

namespace App\Repositories\Crm;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertyRepository
{
    public function create(array $data): array
    {
        $id = DB::table('properties')->insertGetId([
            'uuid' => Str::uuid()->toString(),
            'title' => $data['title'],
            'type' => $data['type'] ?? 'apartment',
            'status' => 'available',
            'price' => $data['price'] ?? 0,
            'location' => $data['location'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return (array) DB::table('properties')->find($id);
    }

    public function getAll()
    {
        return DB::table('properties')->orderBy('created_at', 'desc')->get();
    }
}