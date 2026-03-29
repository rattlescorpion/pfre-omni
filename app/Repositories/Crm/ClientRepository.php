<?php declare(strict_types=1);

namespace App\Repositories\Crm;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientRepository
{
    /**
     * Inserts a new client into the database.
     */
    public function create(array $data): array
    {
        // Generate a unique Client Number (e.g., CLI-X7B29P)
        $clientNo = 'CLI-' . strtoupper(Str::random(6));

        // Insert the data into the clients table and get the new ID
        $id = DB::table('clients')->insertGetId([
            'uuid'       => Str::uuid()->toString(),
            'client_no'  => $clientNo,
            'type'       => $data['type'] ?? 'individual',
            'name'       => $data['name'],
            'phone'      => $data['phone'],
            'email'      => $data['email'] ?? null,
            'created_by' => $data['created_by'] ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Fetch the newly created client
        $client = DB::table('clients')->find($id);

        return (array) $client;
    }

    /**
     * Finds a client by their ID.
     */
    public function find(int $id): ?array
    {
        $client = DB::table('clients')->find($id);
        return $client ? (array) $client : null;
    }
}