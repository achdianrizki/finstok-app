

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            @section('title', __('Tambah Pembayaran :purchase_number', ['purchase_number' => $purchase->purchase_number]))
            {{ __('Tambah Pembayaran :purchase_number', ['purchase_number' => $purchase->purchase_number]) }}</h2>
    </x-slot>

    <form id="outgoing-payment-form" action="{{ route('manager.outgoingpayment.store') }}" method="POST"
        data-parsley-validate>
        @csrf
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="grid grid-cols-2 gap-4">

                <input type="hidden" value="{{ $purchase->id }}" name="purchase_id">

                <div class="space-y-2">
                    {{-- <x-form.label for="receipt_number" :value="__('Nomor Resi')" />
                    <x-form.input id="receipt_number" class="block w-full" type="text" name="receipt_number"
                        :value="old('receipt_number')" required data-parsley-required-message="Nomor Resi wajib diisi" /> --}}

                    <x-form.label for="payment_date" :value="__('Tanggal Pembayaran')" />
                    <x-form.input id="payment_date" class="block w-full flatpickr-input" type="date"
                        name="payment_date" autocomplete="off" required
                        data-parsley-required-message="Tanggal Pembayaran wajib diisi" />

                    <x-form.label for="payment_method" :value="__('Metode Pembayaran')" />
                    <x-form.select id="payment_method" class="block w-full" name="payment_method" required
                        data-parsley-required-message="Metode Pembayaran wajib diisi">
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
                </div>

                <div>
                    <x-form.label for="amount_paid" :value="__('Jumlah Dibayarkan')" class="mb-2" />
                    <x-form.input id="amount_paid" class="block w-full flatpickr-input" type="text" name="amount_paid"
                        :value="old('amount_paid')" required data-parsley-required-message="Jumlah Dibayarkan wajib diisi" />
                    <span id="amount_paid_error" class="text-red-500 text-sm mt-5 hidden">Jumlah dibayarkan tidak boleh
                        lebih besar dari sisa pembayaran.</span>

                    <x-form.label for="note" :value="__('Catatan')" class="mb-2 mt-2" />
                    <textarea id="note" name="note"
                        class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                        rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>

                </div>
            </div>
        </div>

        <div class="grid justify-items-end mt-4 space-y-2">
            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_payed" class="mr-4">Jumlah Pembayaran</label>
                <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="total_payed"
                    id="total_payed" value="Rp {{ number_format($payed_amount, 2, ',', '.') }}" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_unpaid" class="mr-4">Sisa Pembayaran</label>
                <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="total_unpaid"
                    id="total_unpaid" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_price" class="mr-4">Total Price</label>
                <input type="text" class="w-1/2 border-gray-500 bg-gray-100 rounded-md p-2" name="total_price"
                    id="total_price" value="Rp {{ number_format($purchase->total_price, 2, ',', '.') }}" readonly>
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
                $('#amount_paid').on('input', function(e) {
                    let value = e.target.value.replace(/[^,\d]/g, '').toString();

                    let split = value.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/g);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    if (split[1] !== undefined) {
                        rupiah += ',' + split[1].slice(0, 2);
                    }

                    $(this).val(rupiah);
                });

                $('#total_payed').attr('data-initial', $('#total_payed').val());
                calculateRemainingPayment();

                $("#payment_date").flatpickr({
                    dateFormat: "d-m-Y",
                    allowInput: true,
                });

                $('#amount_paid').on('input', function() {
                    let payAmount = parseFloat($(this).val().replace(/\./g, '').replace(',', '.')) || 0;
                    let totalPayed = parseFloat($('#total_payed').attr('data-initial').replace(/Rp\s?/g, '')
                        .replace(/\./g, '').replace(',', '.')) || 0;
                    let totalPrice = parseFloat($('#total_price').val().replace(/Rp\s?/g, '').replace(/\./g, '')
                        .replace(',', '.')) || 0;

                    let remainingPayment = totalPrice - totalPayed;

                    payAmount = parseFloat(payAmount.toFixed(2));
                    remainingPayment = parseFloat(remainingPayment.toFixed(2));

                    if (payAmount > remainingPayment) {
                        $('#amount_paid_error').removeClass('hidden');
                        $(this).addClass('border-red-500');
                        $('#buttonSubmit').prop('disabled', true);
                    } else {
                        $('#amount_paid_error').addClass('hidden');
                        $(this).removeClass('border-red-500');
                        $('#buttonSubmit').prop('disabled', false);
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

                $('#amount_paid').on('input', function() {
                    calculateRemainingPayment();
                });

                function calculateRemainingPayment() {
                    let payAmount = parseFloat($('#amount_paid').val().replace(/\./g, '').replace(',', '.')) || 0;
                    let totalPayed = parseFloat($('#total_payed').attr('data-initial').replace(/Rp\s?/g, '').replace(
                        /\./g, '').replace(',',
                        '.')) || 0;
                    let totalPrice = parseFloat($('#total_price').val().replace(/Rp\s?/g, '').replace(/\./g, '')
                        .replace(',', '.')) || 0;

                    let remainingPayment;

                    if (totalPayed === 0 && payAmount === 0) {
                        remainingPayment = totalPrice;
                    } else {
                        let newTotalPayed = totalPayed + payAmount;
                        remainingPayment = totalPrice - newTotalPayed;
                        console.log(remainingPayment);

                    }

                    // Pastikan tidak negatif
                    remainingPayment = Math.max(remainingPayment, 0);

                    $('#total_unpaid').val(
                        `Rp ${remainingPayment.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                    );
                }


                $('#total_payed').attr('data-initial', $('#total_payed').val());

                $('#outgoing-payment-form').parsley();
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

