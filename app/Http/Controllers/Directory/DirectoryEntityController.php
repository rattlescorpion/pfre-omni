<?php

declare(strict_types=1);

namespace App\Http\Controllers\Directory;

use App\Http\Controllers\Controller;
use App\Models\DirectoryEntity;
use App\Models\DirectoryCommunicationPoint;
use App\Models\DirectoryEntityRelationship;
use App\Models\DirectoryCompanyBranch;
use App\Models\DirectoryAddress;
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

        $directoryEntity->load(['communicationPoints', 'addresses', 'branches.addresses']);
        $relationships = DirectoryEntityRelationship::query()
            ->with(['source:id,display_name,entity_type', 'target:id,display_name,entity_type'])
            ->where(function ($query) use ($directoryEntity) {
                $query->where('source_entity_id', $directoryEntity->id)
                    ->orWhere('target_entity_id', $directoryEntity->id);
            })
            ->whereHas('source', fn ($query) => $query->where('owner_user_id', auth()->id()))
            ->whereHas('target', fn ($query) => $query->where('owner_user_id', auth()->id()))
            ->latest()
            ->get();

        $availableEntities = DirectoryEntity::ownedBy((int) $request->user()->id)
            ->whereKeyNot($directoryEntity->id)
            ->orderBy('display_name')
            ->get(['id', 'display_name', 'entity_type']);

        return view('directory.show', [
            'entity' => $directoryEntity,
            'addresses' => $directoryEntity->addresses,
            'branches' => $directoryEntity->branches,
            'relationships' => $relationships,
            'availableEntities' => $availableEntities,
            'relationshipTypes' => [
                'owner' => 'Owner',
                'director' => 'Director',
                'founder' => 'Founder',
                'employee' => 'Employee',
                'authorised_signatory' => 'Authorised Signatory',
                'partner' => 'Partner',
                'shareholder' => 'Shareholder',
                'family_member' => 'Family Member',
                'representative' => 'Representative',
                'other' => 'Other',
            ],
        ]);
    }

    public function storeRelationship(Request $request, DirectoryEntity $directoryEntity): RedirectResponse
    {
        abort_unless($directoryEntity->owner_user_id === $request->user()->id, 404);

        $data = $request->validate([
            'target_entity_id' => [
                'required', 'integer',
                Rule::exists('directory_entities', 'id')->where('owner_user_id', $request->user()->id),
                Rule::notIn([$directoryEntity->id]),
            ],
            'relationship_type' => ['required', Rule::in([
                'owner', 'director', 'founder', 'employee', 'authorised_signatory',
                'partner', 'shareholder', 'family_member', 'representative', 'other',
            ])],
            'notes' => ['nullable', 'string', 'max:2000'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
        ]);

        DirectoryEntityRelationship::create([
            ...$data,
            'source_entity_id' => $directoryEntity->id,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('directory.show', $directoryEntity)
            ->with('success', 'Relationship added successfully.');
    }

    public function destroyRelationship(
        Request $request,
        DirectoryEntity $directoryEntity,
        DirectoryEntityRelationship $relationship
    ): RedirectResponse {
        abort_unless($directoryEntity->owner_user_id === $request->user()->id, 404);
        abort_unless(
            $relationship->source_entity_id === $directoryEntity->id
            || $relationship->target_entity_id === $directoryEntity->id,
            404
        );
        abort_unless(
            $relationship->source()->where('owner_user_id', $request->user()->id)->exists()
            && $relationship->target()->where('owner_user_id', $request->user()->id)->exists(),
            404
        );

        $relationship->delete();

        return redirect()->route('directory.show', $directoryEntity)
            ->with('success', 'Relationship archived.');
    }

    public function storeBranch(Request $request, DirectoryEntity $directoryEntity): RedirectResponse
    {
        abort_unless($directoryEntity->owner_user_id === $request->user()->id, 404);
        abort_unless($directoryEntity->entity_type === 'company', 404);

        $data = $request->validate([
            'branch_name' => ['required', 'string', 'max:190'],
            'branch_type' => ['required', Rule::in(['head_office', 'registered_office', 'office', 'warehouse', 'site', 'other'])],
            'branch_code' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:254'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if (!empty($data['branch_code']) && DirectoryCompanyBranch::where('company_entity_id', $directoryEntity->id)->where('branch_code', $data['branch_code'])->exists()) {
            return back()->withErrors(['branch_code' => 'This branch code is already used by this company.'])->withInput();
        }

        DirectoryCompanyBranch::create([
            ...$data,
            'company_entity_id' => $directoryEntity->id,
            'is_head_office' => $data['branch_type'] === 'head_office',
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('directory.show', $directoryEntity)->with('success', 'Company branch added.');
    }

    public function storeAddress(Request $request, DirectoryEntity $directoryEntity): RedirectResponse
    {
        abort_unless($directoryEntity->owner_user_id === $request->user()->id, 404);

        $fields = [
            'line_1', 'line_2', 'building_name', 'society_name', 'wing', 'flat_unit_number',
            'road', 'landmark', 'area', 'village', 'taluka_sub_district', 'sub_division',
            'district', 'division', 'state', 'postal_code', 'police_station', 'post_office',
            'survey_number', 'hissa_number', 'cts_number', 'railway_station', 'metro_station',
            'bus_stop', 'fire_station',
        ];
        $rules = ['address_type' => ['required', Rule::in(['primary', 'registered', 'office', 'residential', 'billing', 'correspondence', 'other'])], 'country' => ['required', 'string', 'size:2'], 'notes' => ['nullable', 'string', 'max:2000']];
        foreach ($fields as $field) {
            $rules[$field] = ['nullable', 'string', 'max:' . ($field === 'postal_code' ? 12 : 190)];
        }
        $data = $request->validate($rules);

        DirectoryAddress::create([
            ...$data,
            'entity_id' => $directoryEntity->id,
            'is_primary' => $data['address_type'] === 'primary',
        ]);

        return redirect()->route('directory.show', $directoryEntity)->with('success', 'Address added.');
    }

    public function destroyBranch(Request $request, DirectoryEntity $directoryEntity, DirectoryCompanyBranch $branch): RedirectResponse
    {
        abort_unless($directoryEntity->owner_user_id === $request->user()->id, 404);
        abort_unless($directoryEntity->entity_type === 'company' && $branch->company_entity_id === $directoryEntity->id, 404);
        $branch->delete();

        return redirect()->route('directory.show', $directoryEntity)->with('success', 'Branch archived.');
    }

    public function destroyAddress(Request $request, DirectoryEntity $directoryEntity, DirectoryAddress $address): RedirectResponse
    {
        abort_unless($directoryEntity->owner_user_id === $request->user()->id, 404);
        abort_unless($address->entity_id === $directoryEntity->id, 404);
        $address->delete();

        return redirect()->route('directory.show', $directoryEntity)->with('success', 'Address archived.');
    }

}
