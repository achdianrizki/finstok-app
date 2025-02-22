<x-app-layout x-data="{ showAccountNumber: false }">
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-700">{{ __('Pembayaran') }}</h2>
    </x-slot>

    <form action="{{ route('manager.outgoingpayment.store') }}" method="POST">
        @csrf
        <div class="p-6 bg-white rounded-lg shadow-lg space-y-6">

            <!-- Informasi Supplier -->
            <div class="p-4 bg-gray-100 rounded-md flex justify-between items-center">
                <div>
                    <h3 class="text-md font-semibold text-gray-700">{{ __('Supplier') }}</h3>
                    <span class="text-gray-600 text-lg">{{ $suppliers->name }}</span>
                </div>
                <input type="hidden" name="supplier_id" value="{{ $suppliers->id }}">
                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                <div>
                    <h3 class="text-md font-semibold text-gray-700">Total Belum Dibayar</h3>
                    <span class="text-gray-600 text-lg">Rp
                        {{ number_format($purchase->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Detail Pembayaran -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-form.label for="receipt_number" :value="__('Nomor Pembayaran')" />
                    <x-form.input id="receipt_number" class="w-full" type="text" name="receipt_number" />

                    <x-form.label for="payment_date" :value="__('Tanggal Pembayaran')" />
                    <x-form.input id="payment_date" class="w-full flatpickr-input" type="date" name="payment_date" />
                </div>

                <div x-data="{ showAccountNumber: false }">
                    <x-form.label for="payment_method" :value="__('Metode Pembayaran')" />
                    <x-form.select id="payment_method" class="w-full" name="payment_method"
                        x-on:change="showAccountNumber = ($event.target.value === 'transfer')">
                        <option value="cash">{{ __('Cash') }}</option>
                        <option value="transfer">{{ __('Transfer') }}</option>
                    </x-form.select>

                    <div x-show="showAccountNumber" x-transition x-cloak class="mt-5">
                        <x-form.label for="account_number" :value="__('Nomor Rekening')" />
                        <x-form.input id="account_number" class="w-full" type="text" name="account_number" />
                    </div>
                </div>

            </div>

            <!-- Catatan -->
            <div class="space-y-2">
                <x-form.label for="amount_paid" :value="__('Nominal Bayar')" />
                <x-form.input id="amount_paid" class="w-full" type="number" step="0.01" name="amount_paid" />

                <x-form.label for="note" :value="__('Catatan')" />
                <textarea id="note" name="note"
                    class="w-full border-gray-300 rounded-md focus:ring focus:ring-blue-400 focus:ring-offset-2" rows="3"
                    placeholder="Tambahkan catatan jika diperlukan">{{ old('note') }}</textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end">
                <x-button class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-md transition">
                    <span>{{ __('Bayar Sekarang') }}</span>
                </x-button>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $("#payment_date").flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    minDate: "today"
                });
            });
        </script>
    @endpush

</x-app-layout>
