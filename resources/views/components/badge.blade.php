@props(['type' => 'buy'])

@php
    $classes = match ($type) {
        'buy'    => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        'rent'   => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
        'sell'   => 'bg-accent-100 text-accent-800 dark:bg-accent-900/30 dark:text-accent-300',
        default  => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $slot }}
</span>