<div x-data="{ open: false }" class="relative inline-block text-left">
    <div>
        <button type="button" @click="open = !open"
            class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white dark:bg-dark-eval-1 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
            id="menu-button" aria-expanded="true" aria-haspopup="true">
            {{ $slot }}
        </button>
    </div>
    <!-- Mengubah `origin-top-right` menjadi `origin-top-left` dan menambahkan `left-0` -->
    <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute left-0 z-10 mt-2 w-56 origin-top-left bg-white dark:bg-dark-eval-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" style="display: none;">
        <div class="" role="none">
            {{ $menu }}
        </div>
    </div>
</div>
