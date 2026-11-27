@extends('layouts.app')
@section('content')
<div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6">
    <div><p class="text-sm font-semibold text-blue-700">Project 360°</p><h1 class="text-2xl font-bold text-slate-900">Edit Project</h1></div>
    @if($errors->any())<div class="rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('inventory.projects.update', $project->id) }}" class="space-y-6">@csrf @method('PUT')
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><h2 class="mb-4 text-lg font-semibold">1. Project Identity</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2"><label class="mb-1 block text-sm font-medium">Project / Development Name *</label><input name="name" value="{{ old('name', $project->name) }}" required maxlength="255" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Developer / Promoter (Company 360°)</label><select name="developer_directory_entity_id" class="w-full rounded-lg border-slate-300"><option value="">Select existing company</option>@foreach($developers as $developer)<option value="{{ $developer->id }}" @selected(old('developer_directory_entity_id', $project->developer_directory_entity_id)==$developer->id)>{{ $developer->display_name }}</option>@endforeach</select></div>
                <div><label class="mb-1 block text-sm font-medium">Project Type</label><select name="project_type" class="w-full rounded-lg border-slate-300"><option value="">Select type</option>@foreach(['residential'=>'Residential','commercial'=>'Commercial','mixed_use'=>'Mixed Use','plotted_development'=>'Plotted Development','redevelopment'=>'Redevelopment','other'=>'Other'] as $value=>$label)<option value="{{ $value }}" @selected(old('project_type', $project->project_type)===$value)>{{ $label }}</option>@endforeach</select></div>
                <div><label class="mb-1 block text-sm font-medium">Project Status *</label><select name="status" required class="w-full rounded-lg border-slate-300">@foreach(['Pre-Launch','Under Construction','Ready Possession'] as $status)<option value="{{ $status }}" @selected(old('status', $project->status)===$status)>{{ $status }}</option>@endforeach</select></div>
                <div><label class="mb-1 block text-sm font-medium">Legacy / Primary MahaRERA Reference</label><input name="maharera_reg_no" value="{{ old('maharera_reg_no', $project->maharera_reg_no) }}" maxlength="255" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Locality / Area *</label><input name="location_area" value="{{ old('location_area', $project->location_area) }}" required maxlength="255" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Project Website</label><input type="url" name="website_url" value="{{ old('website_url', $project->website_url) }}" class="w-full rounded-lg border-slate-300"></div>
                <div class="md:col-span-2"><label class="mb-1 block text-sm font-medium">Full Project Address *</label><textarea name="address" rows="2" required class="w-full rounded-lg border-slate-300">{{ old('address', $project->address) }}</textarea></div>
                <div class="md:col-span-2"><label class="mb-1 block text-sm font-medium">Project Description</label><textarea name="description" rows="4" class="w-full rounded-lg border-slate-300">{{ old('description', $project->description) }}</textarea></div>
            </div>
        </section>
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><h2 class="mb-4 text-lg font-semibold">2. Development Overview</h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div><label class="mb-1 block text-sm font-medium">Land Area (sq. metres)</label><input type="number" step="0.01" min="0" name="land_area_sqm" value="{{ old('land_area_sqm', $project->land_area_sqm) }}" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Proposed Buildings</label><input type="number" min="0" name="total_buildings" value="{{ old('total_buildings', $project->total_buildings) }}" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Proposed Wings</label><input type="number" min="0" name="total_wings" value="{{ old('total_wings', $project->total_wings) }}" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Total Units / Plots (proposed)</label><input type="number" min="0" name="total_units" value="{{ old('total_units', $project->total_units) }}" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Launch Date</label><input type="date" name="launch_date" value="{{ old('launch_date', $project->launch_date) }}" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Proposed Completion Date</label><input type="date" name="proposed_completion_date" value="{{ old('proposed_completion_date', $project->proposed_completion_date) }}" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Actual Completion Date</label><input type="date" name="actual_completion_date" value="{{ old('actual_completion_date', $project->actual_completion_date) }}" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Latitude</label><input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $project->latitude) }}" class="w-full rounded-lg border-slate-300"></div>
                <div><label class="mb-1 block text-sm font-medium">Longitude</label><input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $project->longitude) }}" class="w-full rounded-lg border-slate-300"></div>
            </div>
        </section>
        <div class="flex justify-end gap-3"><a href="{{ route('inventory.projects.show', $project->id) }}" class="rounded-lg border px-5 py-2.5 font-semibold text-slate-700">Cancel</a><button class="rounded-lg bg-blue-700 px-6 py-2.5 font-semibold text-white">Save Changes</button></div>
    </form>
</div>
@endsection
