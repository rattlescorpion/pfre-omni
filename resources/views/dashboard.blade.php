{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Welcome Header & Call to Action --}}
    <div class="mb-8 flex flex-col sm:flex-row justify-between sm:items-end gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-800 dark:text-white">
                Good {{ $greeting }}, {{ auth()->user()->name }}!
            </h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 mt-2">
                Welcome to the Omni Platform Dashboard!
            </p>
        </div>
        <a href="{{ route('leads.create') }}"
           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-200 self-start sm:self-auto">
            + Add New Lead
        </a>
    </div>

    {{-- KPI Cards – Clean & Coloured Top Borders --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        {{-- Active Leads --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-t-4 border-blue-500">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Active Leads</h2>
            <p class="text-5xl font-black text-blue-600 dark:text-blue-400 mt-2">
                {{ $kpis['active_leads'] }}
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-4">Across all Mumbai locations</p>
        </div>

        {{-- Pending Rent --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-t-4 border-red-500">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Pending Rent</h2>
            <p class="text-5xl font-black text-red-600 dark:text-red-400 mt-2">
                ₹{{ number_format($kpis['pending_rent']) }}
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-4">Requires immediate follow-up</p>
        </div>

        {{-- Open Tickets --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-t-4 border-amber-500">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Open Tickets</h2>
            <p class="text-5xl font-black text-amber-500 dark:text-amber-400 mt-2">
                {{ $kpis['open_tickets'] }}
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-4">Facility & Support requests</p>
        </div>

        {{-- Revenue This Month --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-t-4 border-green-500">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Revenue (Month)</h2>
            <p class="text-5xl font-black text-green-600 dark:text-green-400 mt-2">
                ₹{{ number_format($kpis['monthly_revenue']) }}
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-4">Current financial period</p>
        </div>
    </div>

    {{-- Optional: recent leads or quick actions can be added below if desired --}}
</div>
@endsection