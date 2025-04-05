<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Retur Penjualan :sale_number', ['sale_number' => $sale->sale_number]) }}</h2>

            <a href="{{ route('manager.report.incomingPayment.export.allPdf', $sale->id) }}"
                class="flex items-center text-sm text-white bg-red-500 hover:bg-red-600 px-2 py-1 border rounded-md"
                role="menuitem" tabindex="-1" id="menu-item-0">
                <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                {{-- <span>Download Faktur</span> --}}
                <span>Download Bukti</span>
            </a>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-md shadow-md">

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <x-form.label for="sale_number" :value="__('Nomor Penjualan')" />
                <x-form.input id="sale_number" class="block w-full" type="text" name="sale_number" :value="old('sale_number', $sale->sale_number)"
                    readonly :disabled="true" />

                <x-form.label for="sale_date" :value="__('Tanggal Penjualan')" />
                <x-form.input id="sale_date" class="block w-full flatpickr-input" type="date" name="sale_date"
                    :value="old('name', $sale->sale_date)" readonly :disabled="true" />

                <x-form.label for="buyer_id" :value="__('Pelanggan')" />
                <x-form.input id="buyer_id" class="block w-full flatpickr-input" type="text" name="buyer_id"
                    :value="old('buyer_id', $buyer->name)" readonly :disabled="true" />

                <x-form.label for="salesman_id" :value="__('Sales')" />
                <x-form.input id="salesman_id" class="block w-full flatpickr-input" type="text" name="salesman_id"
                    :value="old('salesman_id', $salesman ? $salesman->name : __('Sales tidak ditambahkan'))" readonly :disabled="true" />

                <x-form.label for="tax" :value="__('Pajak')" />
                <x-form.input id="tax" class="block w-full flatpickr-input" type="text" name="tax"
                    :value="old('tax', $sale->tax == 0.0 ? 'NON-PPN' : 'PPN 11%')" readonly :disabled="true" />

            </div>

            <div>
                <x-form.label for="information" :value="__('Keterangan')" class="mb-2" />
                <textarea id="information" name="information"
                    class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 bg-gray-200 dark:bg-dark-eval-1 dark:text-gray-300"
                    rows="3" placeholder="Deskripsi barang" readonly disabled>{{ old('information', $sale->information) }}</textarea>

                <x-form.label for="due_date_duration" :value="__('Durasi Jatuh Tempo (hari)')" class="mb-2" />
                <button type="button"
                    class="px-4 py-2 bg-purple-500 text-white rounded-md duration-btn mb-2">{{ old('due_date_duration', $sale->due_date_duration) }}</button>

                <x-form.label for="due_date" :value="__('Tanggal Jatuh Tempo')" />
                <x-form.input id="due_date" class="block w-full flatpickr-input" type="date" name="due_date"
                    :value="old('due_date', $sale->due_date)" readonly :disabled="true" />
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
                                    name="price_per_item[]"
                                        class="item-sale_price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                        value="{{ number_format($item->pivot->sale_price, 2, ',', '.') }}" readonly>
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
                                        @php
                                            $subtotal = $item->pivot->sale_price * $item->pivot->qty_sold;

                                            $discount1 = $subtotal * ($item->pivot->discount1 / 100);

                                            $discount2 = $subtotal * ($item->pivot->discount2 / 100);

                                            $discount3 = $subtotal * ($item->pivot->discount3 / 100);
                                        @endphp
                                        value="{{ number_format($subtotal - $discount1 - $discount2 - $discount3, 2, ',', '.') }}"
                                        readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text"
                                        class="item-sub-total w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                        value="{{ number_format($subtotal, 2, ',', '.') }}"
                                        readonly>
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
            <label for="sub_total" class="mr-4">Sub Total</label>
            <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="sub_total"
                id="sub_total" readonly
                value="Rp {{ number_format(floor($sale->sub_total * 100) / 100, 2, ',', '.') }}
">
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_discount1" class="mr-4">Diskon 1</label>
            <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="total_discount1"
                id="total_discount1" readonly value="Rp {{ number_format($sale->discount1_value, 2, ',', '.') }}">
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_discount2" class="mr-4">Diskon 2</label>
            <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="total_discount2"
                id="total_discount2" readonly value="Rp {{ number_format($sale->discount2_value, 2, ',', '.') }}">
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_discount3" class="mr-4">Diskon 3</label>
            <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="total_discount3"
                id="total_discount3" readonly value="Rp {{ number_format($sale->discount3_value, 2, ',', '.') }}">
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="tax" class="mr-4">PPN 11%</label>
            <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="tax"
                id="taxRate" readonly value="Rp {{ number_format($sale->tax, 2, ',', '.') }}">
        </div>

        {{-- <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_payed" class="mr-4">Jumlah Pembayaran</label>
            <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="total_payed"
                id="total_payed" value="Rp {{ number_format($total_payed, 2, ',', '.') }}" readonly>
        </div> --}}

        {{-- <div class="flex justify-between items-center w-full max-w-md">
            <label for="remaining_payment" class="mr-4">Sisa Pembayaran</label>
            <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="remaining_payment"
                id="remaining_payment" value="Rp {{ number_format($remaining_payment, 2, ',', '.') }}" readonly>
        </div> --}}

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_price" class="mr-4">Total Price</label>
            <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="total_price"
                id="total_price" value="Rp {{ number_format($sale->total_price, 2, ',', '.') }}" readonly>
        </div>
    </div>

    <form action="{{ route('manager.return.sale.create', $sale->id) }}" method="POST">
        @csrf
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="space-y-2">
                <x-form.label for="reason" :value="__('Alasan Retur')" />
                <textarea id="reason" name="reason"
                    class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                    rows="3" required></textarea>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold">{{ __('Pilih Barang untuk Diretur') }}</h3>
                <hr class="my-2 border-gray-300">
            </div>

            <div class="mt-5 space-y-2">
                <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-lg shadow-md">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-max border border-gray-300 shadow-md table-auto">
                            <thead
                                class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-2 text-center border-b border-gray-300">Nama Barang</th>
                                    <th class="px-4 py-2 text-center border-b border-gray-300">Qty</th>
                                    <th class="px-4 py-2 text-center border-b border-gray-300">Harga</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-300">
                                @foreach ($sale->items as $item)
                                    <tr class="border-b border-gray-300">
                                        <td class="px-4 py-2 text-center">{{ $item->name }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="number" name="qty[]" max="{{ $item->pivot->qty }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded-md text-center"
                                                required>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="hidden" name="items[]" value="{{ $item->id }}">
                                            <input type="text" name="price_per_item[]"
                                                value="{{ $item->pivot->sale_price }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                                readonly>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid justify-items-end mt-4 space-y-2">
                <x-button class="gap-2">
                    <span>{{ __('Submit Retur') }}</span>
                </x-button>
            </div>
        </div>
    </form>
</x-app-layout>
