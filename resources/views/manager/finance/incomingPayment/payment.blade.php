<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Pembayaran :sale_number', ['sale_number' => $sale->sale_number]) }}</h2>
    </x-slot>

    <div class="p-6 bg-white rounded-md shadow-md">

        <div class="flex flex-col md:flex-row md:justify-between gap-4 my-3">
            <x-dropdown.dropdown>
                <x-slot name="slot">
                    <x-heroicon-o-download class="w-6 h-6 dark:text-white" aria-hidden="true" />
                </x-slot>

                <x-slot name="menu">
                    <a href="{{ route('sales.export.pdf', $sale->id) }}"
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
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <x-form.label for="sale_number" :value="__('Nomor Penjualan')" />
                <x-form.input id="sale_number" class="block w-full" type="text" name="sale_number" :value="old('sale_number', $sale->sale_number)"
                    readonly />

                <x-form.label for="sale_date" :value="__('Tanggal Penjualan')" />
                <x-form.input id="sale_date" class="block w-full flatpickr-input" type="date" name="sale_date"
                    :value="old('name', $sale->sale_date)" readonly />

                <x-form.label for="buyer_id" :value="__('Pelanggan')" />
                <x-form.input id="buyer_id" class="block w-full flatpickr-input" type="text" name="buyer_id"
                    :value="old('buyer_id', $buyer->name)" readonly />

                <x-form.label for="salesman_id" :value="__('Sales')" />
                <x-form.input id="salesman_id" class="block w-full flatpickr-input" type="text" name="salesman_id"
                    :value="old('salesman_id', $salesman ? $salesman->name : __('Sales tidak ditambahkan'))" readonly />

                <x-form.label for="tax" :value="__('Pajak')" />
                <x-form.input id="tax" class="block w-full flatpickr-input" type="text" name="tax"
                    :value="old('tax', $sale->tax == null ? 'NON PPN' : 'PPN 11%')" readonly />

            </div>

            <div>
                <x-form.label for="information" :value="__('Keterangan')" class="mb-2" />
                <textarea id="information" name="information"
                    class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                    rows="3" placeholder="Deskripsi barang" readonly>{{ old('information', $sale->information) }}</textarea>
            </div>

        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-semibold">{{ __('Barang yang Dijual') }}</h3>
        <hr class="my-2 border-gray-300">
    </div>

    <div class="mt-5 space-y-2">

        <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-lg shadow-md mb-5">
            <div class="overflow-x-auto">
                <table class="w-full min-w-max border border-gray-300 shadow-md table-auto" id="items-table">
                    <thead class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-2 text-center border-b border-gray-300 ">Kode Barang</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300 ">Nama Barang</th>
                            <th class="px-2 py-2 text-center border-b border-gray-300 ">Satuan</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300 ">Jumlah Jual</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300 ">Harga Jual per pcs</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300 ">Diskon (%)</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300 ">Total Harga Setelah Diskon
                            </th>
                            <th class="px-4 py-2 text-center border-b border-gray-300 ">Total Harga Sebelum Diskon
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        @foreach ($sale->items as $index => $item)
                            <tr>
                                <td class="px-1 py-2">
                                    <input type="text"
                                        class="item-code w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                        value="{{ $item->code }}" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text"
                                        class="item-name w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100"
                                        value="{{ $item->name }}" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text"
                                        class="item-unit w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                        value="{{ $item->unit }}" readonly>
                                </td>
                                <td class="px-7 py-2">
                                    <input type="text"
                                        class="item-qty-sold w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                        value="{{ $item->pivot->qty_sold }}" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text"
                                        class="item-sale_price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                        value="{{ $item->pivot->sale_price }}" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <div class="flex space-x-1">
                                        <input type="text"
                                            class="item-discount1 w-8 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            value="{{ $item->pivot->discount1 ? $item->pivot->discount1 : 0 }}"
                                            readonly>
                                        <input type="text"
                                            class="item-discount2 w-8 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            value="{{ $item->pivot->discount2 ? $item->pivot->discount2 : 0 }}"
                                            readonly>
                                        <input type="text"
                                            class="item-discount3 w-8 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            value="{{ $item->pivot->discount3 ? $item->pivot->discount3 : 0 }}"
                                            readonly>
                                        <input type="text"
                                            class="ad w-8 px-1 py-1 border border-gray-300 rounded-md text-center bg-gray-100"
                                            placeholder="AD" readonly>
                                    </div>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text"
                                        class="item-total-price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                        readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text"
                                        class="item-sub-total w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                        readonly>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5">
            <h3 class="text-lg font-semibold">
                {{ __('Pembayaran :sale_number', ['sale_number' => $sale->sale_number]) }}
            </h3>
            <hr class="my-2 border-gray-300">
        </div>

        <div class="mt-5 space-y-4">
            <a href="{{ route('manager.incomingpayment.payment', $sale->id) }}" id="add-item"
                class="mt-2 px-4 my-5 py-2 bg-purple-500 text-white rounded">+ Tambah
                Pembayaran
            </a>

            <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-lg shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max border border-gray-300 shadow-md table-auto" id="items-table">
                        <thead class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Nomor Resi</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Tanggal Pembayaran</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Metode Pembayaran
                                </th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Jumlah Dibayarkan
                                </th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Sisa Pembayaran</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Total Dibayarkan</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-300">
                            @foreach ($incomingPayments as $incoming_payment)
                                <tr class="border-b border-gray-300">
                                    <td class="px-1 py-2">
                                        <input type="text"
                                            class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            value="{{ $incoming_payment->invoice_number }}" readonly>
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="text"
                                            class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            value="{{ $incoming_payment->payment_date }}" readonly>
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="text"
                                            class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            value="{{ $incoming_payment->payment_method }}" readonly>
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="text"
                                            class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                            value="{{ $incoming_payment->pay_amount }}" readonly>
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="text"
                                            class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                            value="{{ $incoming_payment->remaining_payment }}" readonly>
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="text"
                                            class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                            value="{{ $incoming_payment->total_paid }}" readonly>
                                    </td>
                                    <td class="px-1 py-2">
                                        <a href="{{ route('incomingPayment.export.pdf', $incoming_payment->id) }}"
                                            class="flex items-center  text-sm text-white bg-red-500 hover:bg-red-600 w-full px-2 py-1 border rounded-md"
                                            role="menuitem" tabindex="-1" id="menu-item-0">
                                            <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                                            <span>Bukti</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="grid justify-items-end mt-4 space-y-2">

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_payed" class="mr-4">Jumlah Pembayaran</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_payed"
                    id="total_payed" value="{{ number_format($total_payed, 2, '.', '') }}" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="remaining_payment" class="mr-4">Sisa Pembayaran</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="remaining_payment"
                    id="remaining_payment" value="{{ number_format($remaining_payment, 2, '.', '') }}" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_price" class="mr-4">Total Price</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_price"
                    id="total_price" value="{{ number_format($sale->total_price, 2, '.', '') }}" readonly>
            </div>
        </div>

        @push('scripts')
            <script>
                $(document).ready(function() {

                    $('#items-table tbody tr').each(function() {
                        calculateTotal($(this));
                    });

                    function calculateTotal(row) {
                        let salePrice = parseFloat(row.find('.item-sale_price').val()) || 0;
                        let qty = parseFloat(row.find('.item-qty-sold').val()) || 0;
                        let discount1 = parseFloat(row.find('.item-discount1').val()) || 0;
                        let discount2 = parseFloat(row.find('.item-discount2').val()) || 0;
                        let discount3 = parseFloat(row.find('.item-discount3').val()) || 0;

                        let originalTotal = salePrice * qty;
                        let totalDiscount = originalTotal * (discount1 + discount2 + discount3) / 100;
                        let finalTotal = originalTotal - totalDiscount;
                        let subTotalPerItem = qty * salePrice;

                        row.find('.item-total-price').val(finalTotal.toFixed(2));
                        row.find('.item-sub-total').val(subTotalPerItem.toFixed(2));
                    }
                });
            </script>
        @endpush

        @push('styles')
            <style>
                .select2-container .select2-selection--single {
                    height: 37px !important;
                    border-radius: 5px;
                    border: 1px solid #9CA3AF;
                    padding-left: 0.30rem;
                    padding-top: 0.25rem;
                    padding-bottom: 0.25rem;
                }

                .select2-container .select2-selection--single .select2-selection__rendered {
                    font-size: 16px;
                    color: #374151;
                }

                .select2-container .select2-selection--single .select2-selection__arrow {
                    height: 37px !important;
                }

                .select2-container--default .select2-results__option--highlighted[aria-selected] {
                    background-color: #3b82f6 !important;
                    color: white !important;
                }

                .select2-container--default .select2-results__option {
                    font-size: 14px;
                    padding: 10px;
                }
            </style>
        @endpush
</x-app-layout>
