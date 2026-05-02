@props(['href' => '#', 'icon' => '', 'active' => false])

@php
    $isActive = $active ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white';
@endphp

<a href="{{ $href }}" 
   class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ $isActive }}"
   {{ $attributes }}>
    @if($icon)
        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
        </svg>
    @endif
    <span>{{ $slot }}</span>
</a>