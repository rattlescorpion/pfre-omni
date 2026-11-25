@extends('layouts.app')
@section('content')
<div class="mx-auto max-w-3xl rounded-xl bg-white p-6 shadow-sm"><h1 class="mb-6 text-xl font-semibold">Edit Project</h1>
@if($errors->any())<div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('inventory.projects.update', $project->id) }}" class="space-y-5">@csrf @method('PUT')
<div><label class="mb-1 block text-sm font-semibold">Project Name</label><input name="name" value="{{ old('name', $project->name) }}" required class="w-full rounded-lg border-slate-300"></div>
<div><label class="mb-1 block text-sm font-semibold">MahaRERA Registration Number</label><input name="maharera_reg_no" value="{{ old('maharera_reg_no', $project->maharera_reg_no) }}" class="w-full rounded-lg border-slate-300"></div>
<div><label class="mb-1 block text-sm font-semibold">Locality / Area</label><input name="location_area" value="{{ old('location_area', $project->location_area) }}" required class="w-full rounded-lg border-slate-300"></div>
<div><label class="mb-1 block text-sm font-semibold">Full Address</label><textarea name="address" rows="3" required class="w-full rounded-lg border-slate-300">{{ old('address', $project->address) }}</textarea></div>
<div><label class="mb-1 block text-sm font-semibold">Project Status</label><select name="status" class="w-full rounded-lg border-slate-300">@foreach(['Under Construction','Ready Possession','Pre-Launch'] as $status)<option value="{{ $status }}" @selected(old('status', $project->status) === $status)>{{ $status }}</option>@endforeach</select></div>
<div class="flex justify-end gap-3 border-t pt-5"><a href="{{ route('inventory.projects.show', $project->id) }}" class="rounded-lg px-5 py-2.5 text-slate-600">Cancel</a><button class="rounded-lg bg-blue-700 px-6 py-2.5 font-semibold text-white">Save Changes</button></div></form></div>
@endsection
