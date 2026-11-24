@extends('layouts.app')

@section('title', 'Contacts & Companies 360°')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-700 dark:text-blue-300">PFRE-OMNI DIRECTORY</p>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Contacts & Companies 360°</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">One profile for each person or organisation, with reusable communication details.</p>
        </div>
        <a href="{{ route('directory.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Add Record</a>
    </div>

    <form method="GET" class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 sm:flex-row">
        <input name="q" value="{{ request('q') }}" placeholder="Search names..." class="min-w-0 flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
        <select name="type" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="">All Records</option>
            <option value="person" @selected(request('type') === 'person')>People</option>
            <option value="company" @selected(request('type') === 'company')>Companies</option>
        </select>
        <button class="rounded-lg border border-gray-300 px-4 py-2 font-semibold dark:border-gray-600 dark:text-white">Search</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($entities as $entity)
                <a href="{{ route('directory.show', $entity) }}" class="flex items-center justify-between gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-gray-900 dark:text-white">{{ $entity->display_name }}</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($entity->entity_type) }} · {{ $entity->status }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $entity->communicationPoints->count() }} communication point(s)</p>
                    </div>
                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-300">Open 360° →</span>
                </a>
            @empty
                <div class="p-10 text-center">
                    <p class="font-semibold text-gray-900 dark:text-white">No records found</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a person or company to start your directory.</p>
                </div>
            @endforelse
        </div>
        <div class="border-t border-gray-200 p-4 dark:border-gray-700">{{ $entities->links() }}</div>
    </div>
</div>
@endsection
