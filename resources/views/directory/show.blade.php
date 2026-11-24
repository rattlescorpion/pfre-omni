@extends('layouts.app')

@section('title', $entity->display_name . ' — 360°')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">
    <a href="{{ route('directory.index') }}" class="text-sm font-semibold text-blue-700 dark:text-blue-300">← Back to Directory</a>

    @if(session('success'))
        <div role="status" class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-200">{{ session('success') }}</div>
    @endif

    <section class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase text-blue-700 dark:bg-blue-900/40 dark:text-blue-200">{{ $entity->entity_type }}</span>
                <h1 class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">{{ $entity->display_name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Record #{{ $entity->id }} · {{ ucfirst($entity->status) }}</p>
            </div>
        </div>
    </section>

    <div class="grid gap-5 md:grid-cols-2">
        <section class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
            <h2 class="font-bold text-gray-900 dark:text-white">Identity Details</h2>
            @if($entity->entity_type === 'person')
                <dl class="mt-4 space-y-3 text-sm">
                    <div><dt class="text-gray-500">First Name</dt><dd class="font-medium dark:text-white">{{ $entity->first_name ?: '—' }}</dd></div>
                    <div><dt class="text-gray-500">Middle Name</dt><dd class="font-medium dark:text-white">{{ $entity->middle_name ?: '—' }}</dd></div>
                    <div><dt class="text-gray-500">Last Name</dt><dd class="font-medium dark:text-white">{{ $entity->last_name ?: '—' }}</dd></div>
                </dl>
            @else
                <dl class="mt-4 space-y-3 text-sm">
                    <div><dt class="text-gray-500">Legal Name</dt><dd class="font-medium dark:text-white">{{ $entity->legal_name ?: '—' }}</dd></div>
                    <div><dt class="text-gray-500">Trading Name</dt><dd class="font-medium dark:text-white">{{ $entity->trading_name ?: '—' }}</dd></div>
                    <div><dt class="text-gray-500">Organisation Type</dt><dd class="font-medium dark:text-white">{{ $entity->organisation_type ?: '—' }}</dd></div>
                    <div><dt class="text-gray-500">Website</dt><dd class="font-medium dark:text-white">{{ $entity->website ?: '—' }}</dd></div>
                </dl>
            @endif
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
            <h2 class="font-bold text-gray-900 dark:text-white">Communication Points</h2>
            <div class="mt-4 space-y-3">
                @forelse($entity->communicationPoints as $point)
                    <div class="flex items-center justify-between gap-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-900">
                        <div><p class="text-xs uppercase text-gray-500">{{ $point->channel_type }}</p><p class="font-medium text-gray-900 dark:text-white">{{ $point->value }}</p></div>
                        <span class="text-xs text-gray-500">{{ $point->pivot->purpose }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No communication points added.</p>
                @endforelse
            </div>
        </section>
    </div>


    @if($entity->entity_type === 'company')
    <section class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Company Branches</h2>
        <form method="POST" action="{{ route('directory.branches.store', $entity) }}" class="mt-4 grid gap-3 md:grid-cols-2">
            @csrf
            <div><label for="branch_name" class="mb-1 block text-sm font-medium">Branch Name</label><input id="branch_name" name="branch_name" required maxlength="190" class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div><label for="branch_type" class="mb-1 block text-sm font-medium">Branch Type</label><select id="branch_type" name="branch_type" required class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"><option value="head_office">Head Office</option><option value="registered_office">Registered Office</option><option value="office">Office</option><option value="warehouse">Warehouse</option><option value="site">Site</option><option value="other">Other</option></select></div>
            <div><label for="branch_code" class="mb-1 block text-sm font-medium">Branch Code</label><input id="branch_code" name="branch_code" maxlength="80" class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div><label for="branch_phone" class="mb-1 block text-sm font-medium">Phone</label><input id="branch_phone" name="phone" maxlength="32" class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div><label for="branch_email" class="mb-1 block text-sm font-medium">Email</label><input id="branch_email" name="email" type="email" maxlength="254" class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div class="md:col-span-2"><label for="branch_notes" class="mb-1 block text-sm font-medium">Notes</label><textarea id="branch_notes" name="notes" rows="2" maxlength="2000" class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea></div>
            <div class="md:col-span-2"><button class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Add Branch</button></div>
        </form>
        <div class="mt-5 space-y-3">
            @forelse($branches as $branch)
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                    <div><p class="font-semibold text-gray-900 dark:text-white">{{ $branch->branch_name }}</p><p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $branch->branch_type)) }} · {{ $branch->branch_code ?: 'No branch code' }} · {{ ucfirst($branch->status) }}</p></div>
                    <form method="POST" action="{{ route('directory.branches.destroy', [$entity, $branch]) }}" onsubmit="return confirm('Archive this branch?')">@csrf @method('DELETE')<button class="text-sm font-semibold text-red-600 hover:underline">Archive</button></form>
                </div>
            @empty
                <p class="text-sm text-gray-500">No branches added yet.</p>
            @endforelse
        </div>
    </section>
    @endif

    <section class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Addresses</h2>
        <form method="POST" action="{{ route('directory.addresses.store', $entity) }}" class="mt-4 grid gap-3 md:grid-cols-2">
            @csrf
            @foreach(['address_type'=>'Address Type','building_name'=>'Building Name','society_name'=>'Society Name','flat_unit_number'=>'Flat / Unit Number','wing'=>'Wing','line_1'=>'Address Line 1','line_2'=>'Address Line 2','road'=>'Road','landmark'=>'Landmark','area'=>'Area / Locality','village'=>'Village','taluka_sub_district'=>'Taluka / Sub-District','sub_division'=>'Sub-Division','district'=>'District','division'=>'Division','state'=>'State','postal_code'=>'PIN / Postal Code','police_station'=>'Police Station','post_office'=>'Post Office','survey_number'=>'Survey Number','hissa_number'=>'Hissa Number','cts_number'=>'CTS Number','railway_station'=>'Railway Station','metro_station'=>'Metro Station','bus_stop'=>'Bus Stop','fire_station'=>'Fire Station'] as $field => $label)
                <div><label for="{{ $field }}" class="mb-1 block text-sm font-medium">{{ $label }}</label>
                @if($field === 'address_type')
                    <select id="{{ $field }}" name="{{ $field }}" required class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"><option value="primary">Primary</option><option value="registered">Registered</option><option value="office">Office</option><option value="residential">Residential</option><option value="billing">Billing</option><option value="correspondence">Correspondence</option><option value="other">Other</option></select>
                @else
                    <input id="{{ $field }}" name="{{ $field }}" maxlength="{{ $field === 'postal_code' ? 12 : 190 }}" @if($field === 'postal_code') inputmode="numeric" @endif class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                @endif</div>
            @endforeach
            <div><label for="country" class="mb-1 block text-sm font-medium">Country Code (ISO-2)</label><input id="country" name="country" value="IN" required minlength="2" maxlength="2" class="w-full rounded-lg border-gray-300 text-sm uppercase dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div class="md:col-span-2"><label for="address_notes" class="mb-1 block text-sm font-medium">Address Notes</label><textarea id="address_notes" name="notes" rows="2" maxlength="2000" class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea></div>
            <div class="md:col-span-2"><button class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Add Address</button></div>
        </form>
        <div class="mt-5 space-y-3">
            @forelse($addresses as $address)
                <div class="flex flex-wrap items-start justify-between gap-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                    <div><p class="font-semibold text-gray-900 dark:text-white">{{ collect([$address->flat_unit_number, $address->wing, $address->building_name, $address->society_name])->filter()->implode(', ') ?: 'Address' }}</p><p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ collect([$address->line_1, $address->line_2, $address->road, $address->landmark, $address->area, $address->village, $address->district, $address->state, $address->postal_code, $address->country])->filter()->implode(', ') }}</p><p class="mt-1 text-xs text-gray-500">{{ ucfirst($address->address_type) }} · {{ collect([$address->survey_number ? 'Survey '.$address->survey_number : null, $address->hissa_number ? 'Hissa '.$address->hissa_number : null, $address->cts_number ? 'CTS '.$address->cts_number : null])->filter()->implode(' · ') }}</p></div>
                    <form method="POST" action="{{ route('directory.addresses.destroy', [$entity, $address]) }}" onsubmit="return confirm('Archive this address?')">@csrf @method('DELETE')<button class="text-sm font-semibold text-red-600 hover:underline">Archive</button></form>
                </div>
            @empty
                <p class="text-sm text-gray-500">No addresses added yet.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Relationships</h2>
                <p class="mt-1 text-sm text-gray-500">Connect people and companies: directors, owners, employees, founders and representatives.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('directory.relationships.store', $entity) }}" class="mt-5 grid gap-3 md:grid-cols-2">
            @csrf
            <div>
                <label for="target_entity_id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Related Person or Company</label>
                <select id="target_entity_id" name="target_entity_id" required class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Select a record</option>
                    @foreach($availableEntities as $option)
                        <option value="{{ $option->id }}" @selected(old('target_entity_id') == $option->id)>{{ $option->display_name }} ({{ ucfirst($option->entity_type) }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="relationship_type" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Relationship</label>
                <select id="relationship_type" name="relationship_type" required class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    @foreach($relationshipTypes as $value => $label)
                        <option value="{{ $value }}" @selected(old('relationship_type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="valid_from" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Valid From (Optional)</label>
                <input id="valid_from" name="valid_from" type="date" value="{{ old('valid_from') }}" class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label for="valid_until" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Valid Until (Optional)</label>
                <input id="valid_until" name="valid_until" type="date" value="{{ old('valid_until') }}" class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div class="md:col-span-2">
                <label for="relationship_notes" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                <textarea id="relationship_notes" name="notes" rows="2" maxlength="2000" class="w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">{{ old('notes') }}</textarea>
            </div>
            @if($availableEntities->isNotEmpty())
                <div class="md:col-span-2"><button type="submit" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Add Relationship</button></div>
            @else
                <p class="text-sm text-gray-500 md:col-span-2">Create another person or company record before adding a relationship.</p>
            @endif
        </form>

        @if($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-700 dark:bg-red-950 dark:text-red-200">
                <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="mt-6 space-y-3">
            @forelse($relationships as $relationship)
                @php
                    $isSource = $relationship->source_entity_id === $entity->id;
                    $related = $isSource ? $relationship->target : $relationship->source;
                @endphp
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                    <div>
                        <a href="{{ route('directory.show', $related) }}" class="font-semibold text-blue-700 hover:underline dark:text-blue-300">{{ $related->display_name }}</a>
                        <p class="mt-1 text-xs text-gray-500">{{ ucfirst($related->entity_type) }} · {{ $isSource ? 'This record → ' : '← This record' }}{{ $relationshipTypes[$relationship->relationship_type] ?? ucfirst(str_replace('_', ' ', $relationship->relationship_type)) }}</p>
                        @if($relationship->notes)<p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $relationship->notes }}</p>@endif
                        @if($relationship->valid_from || $relationship->valid_until)<p class="mt-1 text-xs text-gray-500">Validity: {{ $relationship->valid_from?->format('d M Y') ?? '—' }} to {{ $relationship->valid_until?->format('d M Y') ?? 'Ongoing' }}</p>@endif
                    </div>
                    <form method="POST" action="{{ route('directory.relationships.destroy', [$entity, $relationship]) }}" onsubmit="return confirm('Archive this relationship?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-semibold text-red-600 hover:underline dark:text-red-400">Archive</button>
                    </form>
                </div>
            @empty
                <p class="rounded-lg bg-gray-50 p-4 text-sm text-gray-500 dark:bg-gray-900">No relationships recorded yet.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
        <h2 class="font-bold text-gray-900 dark:text-white">Notes</h2>
        <p class="mt-3 whitespace-pre-wrap text-sm text-gray-700 dark:text-gray-300">{{ $entity->notes ?: 'No notes recorded.' }}</p>
    </section>
    <p class="text-xs text-gray-500">Created {{ $entity->created_at?->format('d M Y, h:i A') }}. Sensitive registration identifiers are not displayed on this profile.</p>
</div>
@endsection
