@extends('layouts.app')

@section('content')
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form action="{{ route('inventory.projects.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div><label for="name" class="mb-1 block text-sm font-semibold text-slate-700">Project Name</label><input id="name" name="name" value="{{ old('name') }}" required maxlength="255" class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600"></div>
                    <div><label for="maharera_reg_no" class="mb-1 block text-sm font-semibold text-slate-700">MahaRERA Registration Number</label><input id="maharera_reg_no" name="maharera_reg_no" value="{{ old('maharera_reg_no') }}" maxlength="255" class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600"></div>
                    <div><label for="location_area" class="mb-1 block text-sm font-semibold text-slate-700">Locality / Area</label><input id="location_area" name="location_area" value="{{ old('location_area') }}" required maxlength="255" placeholder="e.g. Malad West" class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600"></div>
                    <div><label for="address" class="mb-1 block text-sm font-semibold text-slate-700">Full Address</label><textarea id="address" name="address" rows="3" required class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600">{{ old('address') }}</textarea></div>
                    <div><label for="status" class="mb-1 block text-sm font-semibold text-slate-700">Project Status</label><select id="status" name="status" required class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600">@foreach (['Under Construction', 'Ready Possession', 'Pre-Launch'] as $status)<option value="{{ $status }}" @selected(old('status', 'Under Construction') === $status)>{{ $status }}</option>@endforeach</select></div>
                    <div class="flex justify-end gap-3 border-t pt-5"><a href="{{ route('inventory.projects.index') }}" class="rounded-lg px-5 py-2.5 font-semibold text-slate-600 hover:bg-slate-100">Cancel</a><button type="submit" class="rounded-lg bg-blue-700 px-6 py-2.5 font-semibold text-white hover:bg-blue-800">Save Project</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection
