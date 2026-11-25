@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    @if(session('success'))<div class="rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
    <div class="flex flex-wrap items-center justify-between gap-3"><div><p class="text-sm text-slate-500">{{ $property->project_name }}</p><h1 class="text-2xl font-bold text-slate-900">Unit {{ $property->unit_number }}</h1></div><div class="flex gap-2"><a href="{{ route('inventory.properties.index') }}" class="rounded-lg border px-4 py-2 text-sm font-semibold">All Properties</a><a href="{{ route('inventory.properties.edit', $property->id) }}" class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white">Edit Property</a></div></div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach(['Project' => $property->project_name, 'Unit Number' => $property->unit_number, 'Property Type' => $property->type, 'Carpet Area' => number_format((float)$property->carpet_area, 2).' sq. ft.', 'Base Price' => '₹'.number_format((float)$property->base_price, 0), 'Status' => $property->status] as $label => $value)
            <div class="rounded-xl bg-white p-5 shadow-sm"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p><p class="mt-2 text-lg font-semibold text-slate-900">{{ $value }}</p></div>
        @endforeach
    </div>
    <div class="rounded-xl border border-red-200 bg-white p-5">
        <h2 class="font-semibold text-slate-900">Archive Property</h2><p class="mt-1 text-sm text-slate-600">Archived properties are hidden from the active inventory. Their records and ownership links are retained.</p>
        <form method="POST" action="{{ route('inventory.properties.destroy', $property->id) }}" class="mt-4" onsubmit="return confirm('Archive this property?')">@csrf @method('DELETE')<button class="rounded-lg border border-red-300 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Archive Property</button></form>
    </div>
</div>
@endsection
