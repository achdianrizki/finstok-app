<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                @section('title', __('Laporan Penjualan Berdasarkan Sales'))
                {{ __('Laporan Penjualan Berdasarkan Sales') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex flex-col md:flex-row md:justify-between gap-4 my-3">
            <div class="flex gap-4">
                <x-dropdown.dropdown>
                    <x-slot name="slot">
                        <x-heroicon-o-arrow-down-on-square class="w-6 h-6 dark:text-white" aria-hidden="true" />
                    </x-slot>

                    <x-slot name="menu">
                        <form id="pdfForm" action="{{ route('manager.report.sales-by-salesman.export.pdf') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="salesman_id" id="salesman_id">
                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 mb-2 text-sm text-white bg-red-500 hover:bg-red-600 w-full">
                                <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                                <span>Download PDF</span>
                            </button>
                        </form>
                        <form id="excelForm" action="{{ route('manager.report.sales-by-salesman.export.excel') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="salesman_id_excel" id="salesman_id_excel">
                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 w-full">
                                <x-icons.excel class="w-5 h-5" aria-hidden="true" />
                                <span>Download Excel</span>
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown.dropdown>

                <select id="salesman_id_select2" name="salesman_id_select2" class="block w-60 rounded select2">
                    <option value="" selected disabled>Pilih Sales</option>
                    @foreach ($salesmans as $salesman)
                        <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search Input-->
            <div class="w-full md:w-auto">
                <input type="text" id="search" placeholder="Search items..."
                    class="rounded w-full md:w-auto px-4 py-2 dark:bg-dark-eval-1" name="search">
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
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                                Nama Sales</th>
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
        </form>

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

        <div id="paginationNumbers" class="flex items-center justify-center gap-2 mt-4"></div>
        
    </div>

    @push('scripts')
        @include('components.js.dtReportSaleBySalesman')

        <script>
            $(document).ready(function() {
                $('#salesman_id_select2').on('change', function() {
                    $('#salesman_id').val($(this).val());
                    $('#salesman_id_excel').val($(this).val());
                });

                $('#pdfForm').on('submit', function(event) {
                    let salesman_id = $('#salesman_id').val() || null;
                });

                $('#excelForm').on('submit', function(event) {
                    let salesman_id_excel = $('#salesman_id_excel').val() || null;
                    console.log(salesman_id_excel);
                    
                });

                $('#salesman_id_select2').select2();

            });
        </script>
    @endpush

    @push('styles')
        <style>
            .select2-container .select2-selection--single {
                height: 40px !important;
                display: flex;
                align-items: center;
                text-align: center;
                font-size: 16px;
            }

            .select2-container .select2-selection--single .select2-selection__arrow {
                top: 50% !important;
                transform: translateY(-50%) !important;
            }
        </style>
    @endpush

</x-app-layout>
