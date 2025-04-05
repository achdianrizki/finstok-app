<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Laporan Pembelian') }}
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
                        <form id="pdfForm" action="{{ route('manager.report.purchase-items-report.export.pdf') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="period" id="pdfPeriod">
                            <input type="hidden" name="start_date" id="pdfStartDate">
                            <input type="hidden" name="end_date" id="pdfEndDate">
                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 mb-2 text-sm text-white bg-red-500 hover:bg-red-600 w-full">
                                <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                                <span>Download PDF</span>
                            </button>
                        </form>
                        <form id="excelForm" action="{{ route('manager.report.purchase-items-report.export.excel') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="period" id="excelPeriod">
                            <input type="hidden" name="start_date" id="excelStartDate">
                            <input type="hidden" name="end_date" id="excelEndDate">
                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 w-full">
                                <x-icons.excel class="w-5 h-5" aria-hidden="true" />
                                <span>Download Excel</span>
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown.dropdown>

                <x-form.select id="period" class="block w-40 h-10" name="type">
                    <option value="" {{ old('period') == '' ? 'selected' : '' }}>Pilih
                        Periode...</option>
                    <option value="day">
                        Hari ini</option>
                    <option value="month">Bulan ini</option>
                    <option value="custom">Custom</option>
                </x-form.select>

                <div class="flex gap-4 hidden custom">
                    <x-form.input id="start_date" class="block flatpickr-input" type="date" name="start_date"
                        autocomplete="off" placeholder="Dari"
                        style="width: 120px !important; height: 40px !important;" />

                    <x-form.input id="end_date" class="block flatpickr-input" type="date" name="end_date"
                        autocomplete="off" placeholder="Sampai"
                        style="width: 120px !important; height: 40px !important;" />
                </div>
            </div>

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
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nomor Pembelian
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                            Tanggal Pembelian</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                            Nama Pemasok</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="purchaseTable"
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

            $(document).ready(function() {

                $("#start_date").flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
                });

                $("#end_date").flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
                });

                $('#pdfForm').on('submit', function(event) {
                    let period = $('#period').val();
                    $('#pdfPeriod').val(period);

                    let startDate = $('#start_date').val();
                    $('#pdfStartDate').val(startDate);

                    let endDate = $('#end_date').val();
                    $('#pdfEndDate').val(endDate);
                });

                $('#period').change(function() {
                    if ($(this).val() === 'custom') {
                        $('.custom').removeClass('hidden');
                    } else {
                        $('.custom').addClass('hidden');
                        $('#start_date').val("")
                        $('#end_date').val("")
                    }
                });

                $('#excelForm').on('submit', function(event) {
                    let period = $('#period').val();
                    $('#excelPeriod').val(period);

                    let startDate = $('#start_date').val();
                    $('#excelStartDate').val(startDate);

                    let endDate = $('#end_date').val();
                    $('#excelEndDate').val(endDate);
                });

                $('#period').change(function() {
                    if ($(this).val() === 'custom') {
                        $('.custom').removeClass('hidden');
                    } else {
                        $('.custom').addClass('hidden');
                        $('#start_date').val("")
                        $('#end_date').val("")
                    }
                });
            });
        </script>
        @include('components.js.dtReportPurchase')
    @endpush

</x-app-layout>
