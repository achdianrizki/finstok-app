<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                @section('title', __('Stok Barang di ' . $warehouse->name))
                {{ __('Stok Barang di ' . $warehouse->name) }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex flex-col md:flex-row md:justify-between gap-4 my-3">
            <x-dropdown.dropdown>
                <x-slot name="slot">
                    <x-heroicon-o-arrow-down-on-square class="w-6 h-6 dark:text-white" aria-hidden="true" />
                </x-slot>

                <x-slot name="menu">
                    <a href="{{ route('manager.report.items.warehouse.export.pdf', $warehouse->id) }}"
                        class="flex items-center gap-2 px-4 py-2 mb-2 text-sm text-white bg-red-500 hover:bg-red-600"
                        role="menuitem" tabindex="-1" id="menu-item-0">
                        <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                        <span>Download PDF</span>
                    </a>
                    <a href="{{ route('manager.report.items.warehouse.export.excel', $warehouse->id) }}"
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

            <input type="hidden" id="warehouseId" value="{{ $warehouse->id }}">
        </div>

        <div class="overflow-x-auto">
            <table id="export-table" class="min-w-full rounded-md">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 dark:bg-slate-900 dark:text-white text-sm leading-normal">
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">Nama barang</th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                            Kode</th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                            Satuan</th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                            Stok</th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                            Kategori</th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                            Harga/pcs</th>
                        <th
                            class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemsByWarehouse"
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

    <!-- Modal Mutasi Barang -->
    <div id="mutationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Form Mutasi Barang</h2>

            <form id="mutationForm">
                <input type="hidden" id="itemId" name="item_id">
                <input type="hidden" id="purchasePrice" name="price_per_item">

                <div class="mb-4">
                    <label for="fromWarehouse" class="block mb-1">Gudang Asal</label>
                    <input type="text" class="w-full px-3 py-1 border border-gray-300 rounded bg-gray-100" value="{{ $warehouse->name }}" readonly
                        placeholder="">
                    <input type="text" id="fromWarehouse" name="from_warehouse_id" value="{{ $warehouse->id }}" hidden>
                </div>

                <div class="mb-4">
                    <label for="stock_now" class="block mb-1">Stok Saat ini</label>
                    <input type="number" id="stock_now" name="stock_now" class="w-full px-3 py-1 border border-gray-300 rounded bg-gray-100" min="1">
                </div>

                <div class="mb-4">
                    <label for="toWarehouse" class="block mb-1">Gudang Tujuan</label>
                    <select id="toWarehouse" name="toWarehouse" class="w-full select2">
                        <option value="">Pilih Gudang Tujuan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="quantity" class="block mb-1">Jumlah</label>
                    <input type="number" id="quantity" name="quantity" class="w-full px-3 py-1 border rounded" min="1">
                </div>

                <div class="mb-4">
                    <label for="note" class="block mb-1">Keterangan</label>
                    <textarea id="note" name="note" class="w-full px-3 py-1 border rounded" rows="3"
                        placeholder="Keterangan"></textarea>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" onclick="closeMutationModal()"
                        class="mr-3 px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
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
    @include('components.js.dtItemsByWarehousePure')
    @endpush

</x-app-layout>