@extends('layouts.app')

@section('title', $entity->display_name . ' — 360°')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <a href="{{ route('directory.index') }}" class="text-sm font-semibold text-blue-700 dark:text-blue-300">← Back to Directory</a>
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

    <section class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
        <h2 class="font-bold text-gray-900 dark:text-white">Notes</h2>
        <p class="mt-3 whitespace-pre-wrap text-sm text-gray-700 dark:text-gray-300">{{ $entity->notes ?: 'No notes recorded.' }}</p>
    </section>
    <p class="text-xs text-gray-500">Created {{ $entity->created_at?->format('d M Y, h:i A') }}. Sensitive registration identifiers are not displayed on this profile.</p>
</div>
@endsection
