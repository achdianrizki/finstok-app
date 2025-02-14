<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Gudang') }}
            </h2>
            <x-button target="" href="{{ route('manager.warehouses.create') }}" variant="success"
                class="justify-center max-w-xl gap-2">
                <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
                <span>Tambah Gudang</span>
            </x-button>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">

        <div class="flex flex-col md:flex-row md:justify-end gap-4 my-3">

            <!-- Search Input-->
            <div class="w-full md:w-auto">
                <input type="text" id="search" placeholder="Cari sales..."
                    class=" rounded w-full md:w-auto px-4 py-2 dark:bg-dark-eval-1" name="search">
            </div>
        </div>

        <div class="overflow-x-auto">
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <table id="export-table" class="min-w-full rounded-md">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 dark:bg-slate-900 dark:text-white text-sm leading-normal">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama gudang</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                            Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemTable"
                    class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1">

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
        @include('components.js.dtWarehouses')
    @endpush

</x-app-layout>
