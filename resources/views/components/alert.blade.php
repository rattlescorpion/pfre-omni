{{-- resources/views/components/alert.blade.php --}}
@props(['type' => 'info', 'message' => '', 'dismissible' => false])

@php
    $colors = [
        'success' => 'bg-green-50 dark:bg-green-900/20 border-green-400 text-green-800 dark:text-green-300',
        'error' => 'bg-red-50 dark:bg-red-900/20 border-red-400 text-red-800 dark:text-red-300',
        'warning' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400 text-yellow-800 dark:text-yellow-300',
        'info' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-400 text-blue-800 dark:text-blue-300',
    ];
@endphp

<div x-data="{ show: true }" x-show="show"
     class="border-l-4 p-4 mb-6 rounded-r-lg {{ $colors[$type] }}"
     role="alert">
    <div class="flex justify-between">
        <p>{{ $message }}</p>
        @if ($dismissible)
            <button @click="show = false" class="ml-4">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        @endif
    </div>
</div>