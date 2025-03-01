<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight">{{ 'Pembayaran ' . $purchase->purchase_number }}</h2>

            <a href="{{ route('invoice.export.pdf', $purchase->id) }}"
                class="flex items-center text-sm text-white bg-red-500 hover:bg-red-600 px-2 py-1 border rounded-md"
                role="menuitem" tabindex="-1" id="menu-item-0">
                <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                <span>Download Faktur</span>
            </a>


        </div>
    </x-slot>
    <div class="p-6 bg-white rounded-md shadow-md">
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <x-form.label for="purchase_number" :value="__('Nomor Pembelian')" />
                <x-form.input id="purchase_number" class="block w-full" type="text" name="purchase_number"
                    :value="old('purchase_number', $purchase->purchase_number)" />

                <x-form.label for="purchase_date" :value="__('Tanggal Pembelian')" />
                <x-form.input id="purchase_date" class="block w-full flatpickr-input" type="date"
                    name="purchase_date" :value="old('purchase_date', $purchase->purchase_date)" />

                <x-form.label for="supplier_id" :value="__('Supplier')" />
                <x-form.input id="supplier_id" class="block w-full" type="text" name="supplier_name"
                    :value="$purchase->supplier->name" />
                <x-form.input id="supplier_id" class="block w-full" type="hidden" name="supplier_id"
                    :value="old('supplier_id', $purchase->supplier_id)" />

                <x-form.label for="tax" :value="__('Pajak')" />
                <x-form.select id="tax" class="block w-full" name="tax">
                    <option value="" disabled selected>Pilih</option>
                    <option value="ppn" {{ old('tax_type', $purchase->tax_type) == 'ppn' ? 'selected' : '' }}>
                        PPN 11%</option>
                    <option value="non_ppn" {{ old('tax_type', $purchase->tax_type) == 'non_ppn' ? 'selected' : '' }}>
                        NON-PPN
                    </option>
                </x-form.select>
            </div>

            <div>
                <x-form.label for="information" :value="__('Keterangan')" />
                <textarea id="information" name="information"
                    class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                    rows="3" placeholder="Deskripsi barang">{{ old('information', $purchase->information) }}</textarea>
            </div>
        </div>
    </div>


    <div class="mt-5 space-y-5">
        <div class="mt-5">
            <h3 class="text-lg font-semibold">
                {{ __('Barang Yang Di Beli ') }}
            </h3>
            <hr class="my-2 border-gray-300">
        </div>
        <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-lg shadow-md mb-5">
            <div class="overflow-x-auto">
                <table class="w-full min-w-max border border-gray-300 shadow-md table-auto" id="items-table">
                    <thead class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-2 text-center border-b border-gray-300">Kode Barang</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300">Nama Barang</th>
                            <th class="px-2 py-2 text-center border-b border-gray-300">Satuan</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300">Jumlah Jual</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300">Harga Jual per pcs</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300">Diskon (%)</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300">Total Harga Setelah Diskon</th>
                            <th class="px-4 py-2 text-center border-b border-gray-300">Total Harga Sebelum Diskon</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        @foreach ($purchase->items as $index => $item)
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
                                <td class="px-5 py-2">
                                    <input type="text"
                                        class="item-qty-sold w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                        value="{{ $item->pivot->qty }}" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text"
                                        class="item-purchase_price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                        value="{{ number_format($item->pivot->price_per_item, 2, ',', '.') }}"
                                        readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <div class="flex space-x-1">
                                        <input type="text"
                                            class="item-discount1 w-10 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            value="{{ $item->pivot->discount1 ? $item->pivot->discount1 : 0 }}"
                                            readonly>
                                        <input type="text"
                                            class="item-discount2 w-10 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            value="{{ $item->pivot->discount2 ? $item->pivot->discount2 : 0 }}"
                                            readonly>
                                        <input type="text"
                                            class="item-discount3 w-10 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
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
                {{ __('Pembayaran :purchase_number', ['purchase_number' => $purchase->purchase_number]) }}
            </h3>
            <hr class="my-2 border-gray-300">
        </div>

        <x-button :href="route('manager.outgoingpayment.payment', ['purchase' => $purchase->id])" variant="success" class="justify-center max-w-xl gap-2" size="sm">
            <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
            <span>Bayar</span>
        </x-button>


        <table class="w-full border border-gray-300 mt-2 shadow-md rounded-lg overflow-hidden" id="items-table">
            <thead class="bg-gray-200 text-gray-700 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Nomor Resi</th>
                    <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Tanggal Pembayaran</th>
                    <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Note</th>
                    <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Metode Pembayaran</th>
                    <th class="px-4 py-2 text-right border-b border-gray-300 w-2/12">Total Bayar</th>
                    <th class="px-4 py-2 text-right border-b border-gray-300 w-2/12">Jumlah yg Belum Dibayar</th>
                    <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Print</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300">
                @forelse ($purchase->payments as $payment)
                    <tr>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $payment->receipt_number }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $payment->payment_date }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $payment->description }}</td>
                        <td class="px-4 py-2 border-b border-gray-300">{{ $payment->payment_method }}</td>
                        <td class="px-4 py-2 text-right border-b border-gray-300">
                            {{ number_format($payment->amount_paid, 2) }}</td>
                        <td class="px-4 py-2 text-right border-b border-gray-300">
                            {{ number_format($payment->total_unpaid, 2) }}</td>
                        <td>
                            <a href="{{ route('outgoingPayment.export.pdf', $payment->id) }}"
                                class="flex items-center  text-sm text-white bg-red-500 hover:bg-red-600 w-full px-2 py-1 border rounded-md"
                                role="menuitem" tabindex="-1" id="menu-item-0">
                                <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                                <span>Bukti</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                            {{ __('Belum ada pembayaran') }}</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <div class="grid justify-items-end mt-4 space-y-2">
        <div class="flex justify-between items-center w-full max-w-md">
            <label for="sub_total" class="mr-4">Sub Total</label>
            <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="sub_total" id="sub_total"
                readonly value="Rp {{ number_format($purchase->sub_total, 2, ',', '.') }}">
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_discount1" class="mr-4">Diskon 1</label>
            <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_discount1"
                id="total_discount1" readonly
                value="Rp {{ number_format($purchase->total_discount1, 2, ',', '.') }}">
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_discount2" class="mr-4">Diskon 2</label>
            <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_discount2"
                id="total_discount2" readonly
                value="Rp {{ number_format($purchase->total_discount2, 2, ',', '.') }}">
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_discount3" class="mr-4">Diskon 3</label>
            <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_discount3"
                id="total_discount3" readonly
                value="Rp {{ number_format($purchase->total_discount3, 2, ',', '.') }}">
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="tax" class="mr-4">PPN 11%</label>
            <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="tax" id="taxRate"
                readonly value="Rp {{ number_format($purchase->tax, 2, ',', '.') }}">
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_price" class="mr-4">Total Price</label>
            <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_price" id="total_price"
                readonly value="Rp {{ number_format($purchase->total_price, 2, ',', '.') }}">
        </div>
    </div>

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
