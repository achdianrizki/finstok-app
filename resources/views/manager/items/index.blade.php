<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Barang') }}
            </h2>
            <x-button target="" href="{{ route('manager.items.create') }}" variant="success"
                class="justify-center max-w-xl gap-2">
                <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
                <span>Tambah Barang</span>
            </x-button>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex flex-col md:flex-row md:justify-between gap-4 my-3">
            <x-dropdown.dropdown>
                <x-slot name="slot">
                    <x-heroicon-o-arrow-down-on-square class="w-6 h-6 dark:text-white" aria-hidden="true" />
                </x-slot>

                <x-slot name="menu">
                    <a href="{{ route('items.export.pdf') }}"
                        class="flex items-center gap-2 px-4 py-2 mb-2 text-sm text-white bg-red-500 hover:bg-red-600"
                        role="menuitem" tabindex="-1" id="menu-item-0">
                        <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                        <span>Download PDF</span>
                    </a>
                    <a href="{{ route('items.export.excel') }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700"
                        role="menuitem" tabindex="-1" id="menu-item-1">
                        <x-icons.excel class="w-5 h-5" aria-hidden="true" />
                        <span>Download Excel</span>
                    </a>
                </x-slot>
            </x-dropdown.dropdown>

            <!-- Search Input-->
            <div class="w-full md:w-auto">
                <input type="text" id="search" placeholder="Search items..."
                    class=" rounded w-full md:w-auto px-4 py-2 dark:bg-dark-eval-1" name="search">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="export-table" class="min-w-full rounded-md">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 dark:bg-slate-900 dark:text-white text-sm leading-normal">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">Nama barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">Satuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemTable" class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1">

                </tbody>
            </table>
        </div>
        


        <!-- Pagination Controls -->
        <div class="mt-4 flex items-center justify-center">
            <x-button id="prevPage" class="bg-blue-500 text-white p-2 rounded" variant="primary">
                <x-heroicon-o-chevron-double-left class="w-4 h-4" aria-hidden="true" />
            </x-button>

            <span id="currentPage"
                class="mx-4 p-2 border min-w-[40px] text-center rounded bg-gray-100 dark:bg-dark-eval-1 dark:border-dark-eval-1 ">1</span>

            <x-button id="nextPage" variant="primary" class="">
                <x-heroicon-o-chevron-double-right class="w-4 h-4" aria-hidden="true" />
            </x-button>
        </div>

    </div>

    @push('scripts')
        <script>
            function toggleDetails(index) {
                const detailRow = document.getElementById(`details-${index}`);
                if (detailRow.classList.contains('hidden')) {
                    detailRow.classList.remove('hidden');
                } else {
                    detailRow.classList.add('hidden');
                }
            }
        </script>
        @include('components.js.dtItems')
    @endpush

</x-app-layout>
