<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">{{ 'Pembayaran ' . $purchase->purchase_number }}</h2>
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
    <div class="mt-6">
        <h3 class="text-lg font-semibold">{{ __('Riwayat Pembayaran') }}</h3>
        <hr class="my-2 border-gray-300">
    </div>

    <x-button :href="route('manager.outgoingpayment.payment', ['purchase' => $purchase->id])" variant="success" class="justify-center max-w-xl gap-2" size="sm">
        <x-heroicon-o-plus class="w-6 h-6" aria-hidden="true" />
        <span>Bayar</span>
    </x-button>


    <div class="mt-5 space-y-2">
        <table class="w-full border border-gray-300 mt-2 shadow-md rounded-lg overflow-hidden" id="items-table">
            <thead class="bg-gray-200 text-gray-700 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Nomor Resi</th>
                    <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Tanggal Pembayaran</th>
                    <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Note</th>
                    <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Metode Pembayaran</th>
                    <th class="px-4 py-2 text-right border-b border-gray-300 w-2/12">Total Harga</th>
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
                            {{ number_format($payment->total_price, 2) }}</td>
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
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                            {{ __('Belum ada pembayaran') }}</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <div class="grid justify-items-end mt-4 space-y-2">
        <div class="flex justify-between items-center w-full max-w-md">
            <label for="sub_total" class="mr-4">Pembayaran</label>
            <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="sub_total" id="sub_total" readonly>
        </div>

        <div class="flex justify-between items-center w-full max-w-md">
            <label for="total_discount" class="mr-4">Sisa Pembayaran</label>
            <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_discount" id="total_discount"
                readonly>
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
