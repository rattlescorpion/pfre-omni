<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Property to Inventory</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('inventory.properties.store') }}" method="POST" class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    @csrf
                    <div class="md:col-span-2">
                        <label for="project_id" class="mb-1 block text-sm font-semibold text-slate-700">Project</label>
                        <select id="project_id" name="project_id" required class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600">
                            <option value="">Select project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @if ($projects->isEmpty())
                            <p class="mt-1 text-sm text-amber-700">Create a project before adding its properties.</p>
                        @endif
                    </div>

                    <div>
                        <label for="unit_number" class="mb-1 block text-sm font-semibold text-slate-700">Unit / Flat Number</label>
                        <input id="unit_number" name="unit_number" value="{{ old('unit_number') }}" required maxlength="100" placeholder="e.g. A-1204" class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600">
                    </div>
                    <div>
                        <label for="type" class="mb-1 block text-sm font-semibold text-slate-700">Property Type</label>
                        <select id="type" name="type" required class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600">
                            @foreach (['Residential', 'Commercial', 'Plot'] as $type)
                                <option value="{{ $type }}" @selected(old('type', 'Residential') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="carpet_area" class="mb-1 block text-sm font-semibold text-slate-700">Carpet Area (sq. ft.)</label>
                        <input id="carpet_area" name="carpet_area" type="number" min="0.01" step="0.01" value="{{ old('carpet_area') }}" required class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600">
                    </div>
                    <div>
                        <label for="base_price" class="mb-1 block text-sm font-semibold text-slate-700">Base Price (₹)</label>
                        <input id="base_price" name="base_price" type="number" min="0" step="0.01" value="{{ old('base_price') }}" required class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600">
                    </div>
                    <div>
                        <label for="status" class="mb-1 block text-sm font-semibold text-slate-700">Inventory Status</label>
                        <select id="status" name="status" class="w-full rounded-lg border-slate-300 focus:border-blue-600 focus:ring-blue-600">
                            @foreach (['Available', 'Blocked', 'Sold'] as $status)
                                <option value="{{ $status }}" @selected(old('status', 'Available') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2 flex justify-end gap-3 border-t pt-5">
                        <a href="{{ route('inventory.properties.index') }}" class="rounded-lg px-5 py-2.5 font-semibold text-slate-600 hover:bg-slate-100">Cancel</a>
                        <button type="submit" @disabled($projects->isEmpty()) class="rounded-lg bg-blue-700 px-6 py-2.5 font-semibold text-white hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-50">Save Property</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
