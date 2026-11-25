@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl rounded-xl bg-white p-6 shadow-sm">
    <div class="mb-6 flex items-center justify-between"><h1 class="text-xl font-semibold text-slate-900">Edit Property</h1><a href="{{ route('inventory.properties.show', $property->id) }}" class="text-sm font-semibold text-blue-700">Back to details</a></div>
    @if ($errors->any())<div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('inventory.properties.update', $property->id) }}" class="grid grid-cols-1 gap-5 md:grid-cols-2">
        @csrf @method('PUT')
        <div class="md:col-span-2"><label class="mb-1 block text-sm font-semibold">Project</label><select name="project_id" required class="w-full rounded-lg border-slate-300">@foreach($projects as $project)<option value="{{ $project->id }}" @selected(old('project_id', $property->project_id) == $project->id)>{{ $project->name }}</option>@endforeach</select></div>
        <div><label class="mb-1 block text-sm font-semibold">Unit / Flat Number</label><input name="unit_number" value="{{ old('unit_number', $property->unit_number) }}" required maxlength="100" class="w-full rounded-lg border-slate-300"></div>
        <div><label class="mb-1 block text-sm font-semibold">Property Type</label><select name="type" class="w-full rounded-lg border-slate-300">@foreach(['Residential','Commercial','Plot'] as $type)<option value="{{ $type }}" @selected(old('type', $property->type) === $type)>{{ $type }}</option>@endforeach</select></div>
        <div><label class="mb-1 block text-sm font-semibold">Carpet Area (sq. ft.)</label><input type="number" min="0.01" step="0.01" name="carpet_area" value="{{ old('carpet_area', $property->carpet_area) }}" required class="w-full rounded-lg border-slate-300"></div>
        <div><label class="mb-1 block text-sm font-semibold">Base Price (₹)</label><input type="number" min="0" step="0.01" name="base_price" value="{{ old('base_price', $property->base_price) }}" required class="w-full rounded-lg border-slate-300"></div>
        <div><label class="mb-1 block text-sm font-semibold">Status</label><select name="status" class="w-full rounded-lg border-slate-300">@foreach(['Available','Blocked','Sold'] as $status)<option value="{{ $status }}" @selected(old('status', $property->status) === $status)>{{ $status }}</option>@endforeach</select></div>
        <div class="md:col-span-2 flex justify-end gap-3 border-t pt-5"><a href="{{ route('inventory.properties.show', $property->id) }}" class="rounded-lg px-5 py-2.5 text-slate-600">Cancel</a><button class="rounded-lg bg-blue-700 px-6 py-2.5 font-semibold text-white">Save Changes</button></div>
    </form>
</div>
@endsection
