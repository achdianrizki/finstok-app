<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Tambah Pembayaran :sale_number', ['sale_number' => $sale->sale_number]) }}</h2>
    </x-slot>

    <form action="{{ route('manager.incomingPayment.store') }}" method="POST">
        @csrf
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="grid grid-cols-2 gap-4">

                <input type="hidden" value="{{ $sale->id }}" name="sale_id">

                <div class="space-y-2">
                    <x-form.label for="invoice_number" :value="__('Nomor Resi')" />
                    <x-form.input id="invoice_number" class="block w-full" type="text" name="invoice_number"
                        :value="old('invoice_number')" />

                    <x-form.label for="payment_date" :value="__('Tanggal Pembayaran')" />
                    <x-form.input id="payment_date" class="block w-full flatpickr-input" type="date"
                        name="payment_date" autocomplete="off" />

                    <x-form.label for="payment_method" :value="__('Metode Pembayaran')" />
                    <x-form.select id="payment_method" class="block w-full" name="payment_method">
                        <option value="" disabled selected>Pilih</option>
                        <option value="tunai" {{ old('payment_method') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer
                        </option>
                    </x-form.select>

                    <div id="transfer-details" class="hidden">
                        <x-form.label for="bank_account_number" :value="__('Nomor Rekening')" class="mb-2" />
                        <x-form.input id="bank_account_number" class="block w-full" type="text"
                            name="bank_account_number" />

                        <x-form.label for="payment_code" :value="__('Kode Pembayaran')" class="mt-2 mb-2" />
                        <x-form.input id="payment_code" class="block w-full" type="text" name="payment_code" />
                    </div>

                    {{-- <x-form.label for="tax" :value="__('Pajak')" />
                <x-form.input id="tax" class="block w-full flatpickr-input" type="text" name="tax"
                    :value="old('tax')" /> --}}
                </div>

                <div>
                    <x-form.label for="pay_amount" :value="__('Jumlah Dibayarkan')" class="mb-2" />
                    <x-form.input id="pay_amount" class="block w-full flatpickr-input" type="number" name="pay_amount"
                        :value="old('pay_amount')" />
                    <span id="pay_amount_error" class="text-red-500 text-sm mt-5 hidden">Jumlah dibayarkan tidak boleh
                        lebih besar dari sisa pembayaran.</span>

                    <x-form.label for="information" :value="__('Keterangan')" class="mb-2 mt-2" />
                    <textarea id="information" name="information"
                        class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                        rows="3" placeholder="Deskripsi pembayaran"></textarea>

                </div>
            </div>
        </div>

        <div class="grid justify-items-end mt-4 space-y-2">
            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_payed" class="mr-4">Jumlah Pembayaran</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_payed" id="total_payed"
                    value="{{ number_format($payed_amount, 2, '.', '') }}" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="remaining_payment" class="mr-4">Sisa Pembayaran</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="remaining_payment"
                    id="remaining_payment" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_price" class="mr-4">Total Price</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_price" id="total_price"
                    value="{{ number_format($sale->total_price, 2, '.', '') }}" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="tax" class="mr-4">PPN 11%</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="tax" id="taxRate"
                    readonly>
            </div>

            <div class="grid justify-items-end">
                <x-button class="gap-2" id="buttonSubmit">
                    <span>{{ __('Submit') }}</span>
                </x-button>
            </div>
    </form>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#total_payed').attr('data-initial', $('#total_payed').val());
                calculateRemainingPayment();

                $("#payment_date").flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    minDate: "today"
                });

                $('#pay_amount').on('input', function() {
                    let payAmount = parseFloat($(this).val()) || 0;
                    let totalPayed = parseFloat($('#total_payed').attr('data-initial')) || 0;
                    let totalPrice = parseFloat($('#total_price').val()) || 0;

                    let remainingPayment = totalPrice - totalPayed;

                    if (payAmount > remainingPayment) {
                        $('#pay_amount_error').removeClass('hidden'); // Tampilkan pesan error
                        $(this).addClass('border-red-500'); // Tambahkan warna merah ke input
                        $('#buttonSubmit').prop('disabled', true); // Disable tombol submit
                    } else {
                        $('#pay_amount_error').addClass('hidden'); // Sembunyikan pesan error
                        $(this).removeClass('border-red-500'); // Hilangkan warna merah dari input
                        $('#buttonSubmit').prop('disabled', false); // Enable tombol submit
                    }
                });

                $('#payment_method').on('change', function() {
                    if ($(this).val() === 'transfer') {
                        $('#transfer-details').removeClass('hidden');
                        $('#account_number, #payment_code').prop('disabled', false);
                    } else {
                        $('#transfer-details').addClass('hidden');
                        $('#account_number, #payment_code').prop('disabled', true);
                    }
                });

                // Hitung ulang saat pay_amount berubah
                $('#pay_amount').on('input', function() {
                    calculateRemainingPayment();
                });

                function calculateRemainingPayment() {
                    let payAmount = parseFloat($('#pay_amount').val()) || 0;
                    let totalPayed = parseFloat($('#total_payed').attr('data-initial')) || 0;
                    let totalPrice = parseFloat($('#total_price').val()) || 0;

                    let remainingPayment = totalPrice - totalPayed;

                    // Jika payAmount lebih besar dari remainingPayment, set remainingPayment ke 0
                    if (payAmount >= remainingPayment) {
                        $('#remaining_payment').val('0.00');
                    } else {
                        let newTotalPayed = totalPayed + payAmount;
                        let updatedRemainingPayment = totalPrice - newTotalPayed;
                        $('#remaining_payment').val(updatedRemainingPayment.toFixed(2));
                    }
                }

                // Simpan nilai awal total_payed agar tidak terus bertambah saat user mengetik
                $('#total_payed').attr('data-initial', $('#total_payed').val());
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
