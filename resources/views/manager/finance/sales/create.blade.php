<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.sales.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah Penjualan') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.sales.store') }}" method="POST">
            @csrf

            <div class="grid gap-6">
                <!-- Buyer Name -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="buyer_name" :value="__('Nama Pembeli')" />
                    <x-form.input id="buyer_name" class="block w-full" type="text" name="buyer_name"
                        :value="old('buyer_name')" placeholder="{{ __('Nama Pembeli') }}" required autofocus />
                </div>

                <!-- Item -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="item_id" :value="__('Barang')" />
                    <x-form.select id="item_id" class="block w-full" name="item_id" required>
                        <option value="" disabled selected>{{ __('Pilih Barang') }}</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-input-error :messages="$errors->get('item_id')" class="mt-2" />
                </div>

                <!-- Quantity Sold -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="qty_sold" :value="__('Jumlah Terjual')" />
                    <x-form.input id="qty_sold" class="block w-full" type="number" name="qty_sold" :value="old('qty_sold')"
                        placeholder="{{ __('Jumlah Terjual') }}" required />
                </div>

                <!-- Payment Method -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="payment_method" :value="__('Metode Pembayaran')" />
                    <x-form.select id="payment_method" class="block w-full" name="payment_method" required>
                        <option value="" disabled selected>{{ __('Pilih Metode Pembayaran') }}</option>
                        <option value="cash">{{ __('Tunai') }}</option>
                        <option value="credit">{{ __('Kredit') }}</option>
                    </x-form.select>
                    <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                </div>

                <!-- Payment Status -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="payment_status" :value="__('Status Pembayaran')" />
                    <x-form.select id="payment_status" class="block w-full" name="payment_status" required>
                        <option value="" disabled selected>{{ __('Pilih Status Pembayaran') }}</option>
                        <option value="paid">{{ __('Lunas') }}</option>
                        <option value="unpaid">{{ __('Belum Lunas') }}</option>
                    </x-form.select>
                    <x-input-error :messages="$errors->get('payment_status')" class="mt-2" />
                </div>

                <!-- Discount -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="discount" :value="__('Diskon')" />
                    <x-form.input id="discount" class="block w-full" type="number" name="discount" :value="old('discount')"
                        placeholder="{{ __('Diskon (dalam angka)') }}" required />
                </div>

                <!-- Down Payment -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="down_payment" :value="__('Uang Muka')" />
                    <x-form.input id="down_payment" class="block w-full" type="number" name="down_payment"
                        :value="old('down_payment')" placeholder="{{ __('Uang Muka') }}" required />
                </div>

                <!-- Total Price -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="total_price" :value="__('Total Harga')" />
                    <x-form.input id="total_price" class="block w-full" type="number" name="total_price"
                        :value="old('total_price')" placeholder="{{ __('Total Harga') }}" required />
                </div>

                <!-- Distributor (Optional) -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="distributor_id" :value="__('Distributor (Opsional)')" />
                    <x-form.select id="distributor_id" class="block w-full" name="distributor_id">
                        <option value="" disabled selected>{{ __('Pilih Distributor') }}</option>
                        @foreach ($distributors as $distributor)
                            <option value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-input-error :messages="$errors->get('distributor_id')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="grid justify-items-end">
                    <x-button class="gap-2">
                        <span>{{ __('Submit') }}</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const itemQuantity = document.getElementById('qty');
            const itemDiscount = document.getElementById('discount');
            const itemTotal = document.getElementById('total_price');

            itemQuantity.addEventListener('input', calculateTotal);
            itemPrice.addEventListener('input', calculateTotal);

            function calculateTotal() {
                const quantity = parseFloat(itemQuantity.value) || 0;
                const price = parseFloat(itemPrice.value) || 0;
                const total = quantity * price;

                itemTotal.value = formatRupiah(total);
            }


            function formatRupiah(angka) {
                let number_string = angka.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                return rupiah;
            }
        </script>
    @endpush
</x-app-layout>
