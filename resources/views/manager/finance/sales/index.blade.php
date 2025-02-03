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
            <div class="mb-5 space-y-2">
                <x-form.label for="item" :value="__('Cari barang')" />
                <div class="custom-select">
                    <input type="text" id="selectInput" name="item_name"
                        class="block w-md py-2 border-gray-400 rounded-md focus:border-gray-400 focus:ring
                    focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-white dark:border-gray-600 dark:bg-dark-eval-1
                    dark:text-gray-300 dark:focus:ring-offset-dark-eval-1"
                        placeholder="Pilih atau ketik..." autocomplete="off">
                    <input type="hidden" id="item_id" name="item_id">
                    <ul id="selectOptions"></ul>
                    <x-input-error :messages="$errors->get('item_id')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Form to submit selected items -->
        <form action="{{ route('manager.sales.create') }}" method="post">
            @csrf
            <div class="overflow-x-auto">
                <table id="export-table" class="min-w-full rounded-md">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 dark:bg-slate-900 dark:text-white text-sm leading-normal">
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">Kuantitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">Harga/pcs</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">Diskon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">Total harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemSaleTable"
                        class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1">
                        <!-- Dynamic rows will be added here -->
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-center">
                <x-button type="submit" variant="primary">
                    Simpan
                </x-button>
            </div>
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
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" id="close-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
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
                                    <label for="modal_qty_sold" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kuantitas</label>
                                    <input type="number" id="modal_qty_sold" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                </div>

                                <div class="col-span-2 sm:col-span-1">
                                    <label for="modal_discount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Diskon</label>
                                    <input type="number" id="modal_discount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                </div>

                                <div class="col-span-2">
                                    <label for="modal_total_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Harga</label>
                                    <input type="text" id="modal_total_price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" readonly>
                                </div>
                            </div>

                            <button type="button" id="save-item" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @include('components.js.select-picker')
    @endpush

    @push('styles')
        <style>
            .custom-select input {
                width: 100%;
                padding: 8px;
                box-sizing: border-box;
                border-radius: 5px;
            }

            .custom-select ul {
                position: absolute;
                max-height: 150px;
                overflow-y: auto;
                background-color: white;
                border: 1px solid #ccc;
                margin-top: 0;
                padding-left: 0;
                list-style-type: none;
                display: none;
                border-radius: 5px;
                font-size: 10px;
            }

            .custom-select ul li {
                padding: 8px;
                cursor: pointer;
            }

            .custom-select ul li:hover {
                background-color: #f1f1f1;
            }
        </style>
    @endpush
</x-app-layout>
