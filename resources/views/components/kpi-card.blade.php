{{-- resources/views/components/kpi-card.blade.php --}}
@props(['title' => '', 'value' => '', 'icon' => '', 'trend' => null, 'trendColor' => 'green'])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
    <div class="p-5">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-10 w-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ $title }}</dt>
                <dd class="flex items-baseline">
                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $value }}</div>
                    @if($trend)
                        <span class="ml-2 text-sm font-semibold text-{{ $trendColor }}-600">
                            {{ $trend }}
                        </span>
                    @endif
                </dd>
            </div>
        </div>
    </div>
</div>