<?php

declare(strict_types=1);

namespace App\Http\Controllers\Directory;

use App\Http\Controllers\Controller;
use App\Models\DirectoryEntity;
use App\Models\DirectoryCommunicationPoint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DirectoryEntityController extends Controller
{
    public function index(Request $request): View
    {
        $entities = DirectoryEntity::ownedBy((int) $request->user()->id)
            ->with('communicationPoints')
            ->when($request->filled('type'), fn ($q) => $q->where('entity_type', $request->string('type')))
            ->when($request->filled('q'), fn ($q) => $q->whereRaw('LOWER(display_name) LIKE ?', ['%' . mb_strtolower((string) $request->string('q')) . '%']))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('directory.index', compact('entities'));
    }

    public function create(Request $request): View
    {
        return view('directory.create', ['type' => $request->query('type', 'person')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'entity_type' => ['required', Rule::in(['person', 'company'])],
            'display_name' => ['required', 'string', 'max:190'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'legal_name' => ['nullable', 'string', 'max:190'],
            'trading_name' => ['nullable', 'string', 'max:190'],
            'organisation_type' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:190'],
            'mobile' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:254'],
            'notes' => ['nullable', 'string', 'max:10000'],
        ]);

        $entity = DB::transaction(function () use ($data, $request) {
            $entity = DirectoryEntity::create([
                ...collect($data)->except(['mobile', 'email'])->all(),
                'owner_user_id' => $request->user()->id,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);

            foreach (['mobile' => 'mobile', 'email' => 'email'] as $field => $channel) {
                if (empty($data[$field])) {
                    continue;
                }

                $normalised = $channel === 'email'
                    ? mb_strtolower(trim($data[$field]))
                    : preg_replace('/[^0-9+]/', '', $data[$field]);

                $point = DirectoryCommunicationPoint::firstOrCreate(
                    ['channel_type' => $channel, 'normalised_value' => $normalised],
                    ['value' => trim($data[$field])]
                );

                $entity->communicationPoints()->attach($point->id, [
                    'purpose' => 'general',
                    'is_primary' => true,
                    'consent_status' => 'unknown',
                ]);
            }

            return $entity;
        });

        return redirect()->route('directory.show', $entity)->with('success', 'Record created successfully.');
    }

    public function show(Request $request, DirectoryEntity $directoryEntity): View
    {
        abort_unless($directoryEntity->owner_user_id === $request->user()->id, 404);
        $directoryEntity->load('communicationPoints');

        return view('directory.show', ['entity' => $directoryEntity]);
    }
}
