@props([
    'isActive' => false,
    'title' => '',
    'collapsible' => false,
])

@php
    $isActiveClasses = $isActive
        ? 'text-white bg-purple-500 shadow-lg hover:bg-purple-600'
        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:bg-dark-eval-2';

    $baseClasses =
        'flex-shrink-0 flex items-center justify-between gap-2 p-2 transition-colors rounded-md overflow-hidden';
@endphp

<a {{ $attributes->merge(['class' => $baseClasses . ' ' . $isActiveClasses]) }}
    x-bind:class="(isSidebarOpen || isSidebarHovered) ? 'pe-24' : 'pe-2'">
    <div class="flex items-center gap-2">
        @if ($icon ?? false)
            {{ $icon }}
        @else
            <x-icons.empty-circle class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        @endif

        <span class="text-base font-medium whitespace-nowrap" x-show="isSidebarOpen || isSidebarHovered">
            {{ $title }}
        </span>
    </div>
</a>

@if ($collapsible)
    <button @click="open = !open" class="p-2 text-gray-400 rounded hover:bg-gray-700 hover:text-white"
        x-show="isSidebarOpen || isSidebarHovered">
        <x-heroicon-o-chevron-down class="w-4 h-4" x-bind:class="{ 'transform rotate-180': open }" />
    </button>
@endif
