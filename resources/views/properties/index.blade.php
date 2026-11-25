@extends('layouts.app')

@section('content')
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 pt-2 sm:px-6 lg:px-8">
        <div><h1 class="text-2xl font-bold text-slate-900">Property Inventory</h1><p class="mt-1 text-sm text-slate-500">Manage units linked to their projects</p></div>
        <a href="{{ route('inventory.properties.create') }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">+ Add Property</a>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Project</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Unit</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Type</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Carpet Area</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Base Price</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($properties as $property)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-4 text-sm font-medium text-slate-900">{{ $property->project_name }}</td>
                                    <td class="px-5 py-4 text-sm text-slate-700"><a class="font-semibold text-blue-700 hover:underline" href="{{ route('inventory.properties.show', $property->id) }}">{{ $property->unit_number }}</a></td>
                                    <td class="px-5 py-4 text-sm text-slate-700">{{ $property->type }}</td>
                                    <td class="px-5 py-4 text-right text-sm tabular-nums text-slate-700">{{ number_format((float) $property->carpet_area, 2) }} sq. ft.</td>
                                    <td class="px-5 py-4 text-right text-sm tabular-nums text-slate-700">₹{{ number_format((float) $property->base_price, 0) }}</td>
                                    <td class="px-5 py-4 text-sm"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">{{ $property->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">No properties have been added yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-5 py-4">{{ $properties->links() }}</div>
            </div>
        </div>
    </div>
@endsection
