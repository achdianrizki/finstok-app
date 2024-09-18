@props([
    'open' => false,
])

<button @click="open = !open" class="p-2 text-gray-400 rounded hover:bg-gray-700 hover:text-white">
    <x-heroicon-o-chevron-down class="w-4 h-4" x-bind:class="{ 'transform rotate-180': open }" />
</button>