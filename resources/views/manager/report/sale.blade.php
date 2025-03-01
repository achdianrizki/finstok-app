<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Penjualan') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex flex-col md:flex-row md:justify-between gap-4 my-3">
            <x-dropdown.dropdown>
                <x-slot name="slot">
                    <x-heroicon-o-download class="w-6 h-6 dark:text-white" aria-hidden="true" />
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

        <!-- Form to submit selected items -->
        <form action="{{ route('manager.sales.create') }}" method="post">
            @csrf
            <div class="overflow-x-auto">
                <table id="export-table" class="min-w-full rounded-md">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 dark:bg-slate-900 dark:text-white text-sm leading-normal">
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nomor Penjualan
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                                Tanggal Penjualan</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                                Nama Pelanggan</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemTable"
                        class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1">
                        <!-- Dynamic rows will be added here -->
                    </tbody>
                </table>
            </div>

            {{-- <div class="mt-4 flex items-center justify-center">
                <x-button type="submit" variant="primary">
                    Simpan
                </x-button>
            </div> --}}
        </form>

        <!-- Modal -->
        <div id="crud-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            <p id="items_name" class="text-2xl"></p>
                            <p id="items_code"></p>
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            id="close-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form id="modalForm">
                        <div class="p-4 md:p-5">
                            <div class="grid gap-4 mb-4 grid-cols-2">
                                <input type="hidden" id="modal_item_name">
                                <input type="hidden" id="modal_item_code">
                                <input type="hidden" id="modal_item_price">

                                <div class="col-span-2">
                                    <label for="modal_qty_sold"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kuantitas</label>
                                    <input type="number" id="modal_qty_sold"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        required>
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="modal_discount"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Diskon</label>
                                    <input type="number" id="modal_discount"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                </div>

                                <div class="col-span-2">
                                    <label for="modal_total_price"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total
                                        Harga</label>
                                    <input type="text" id="modal_total_price"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                        readonly>
                                </div>
                            </div>

                            <button type="button" id="save-item"
                                class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('components.js.dtReportSale')
    @endpush

</x-app-layout>
