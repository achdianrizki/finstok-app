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

                <!-- Distributor (Optional) -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="distributor_id" :value="__('Distributor')" />
                    <x-form.select id="distributor_id" class="block w-full" name="distributor_id">
                        <option value="" disabled selected>{{ __('Pilih Distributor') }}</option>
                        @foreach ($distributors as $distributor)
                            <option value="{{ $distributor->id }}" data-address="{{ $distributor->address }}"
                                data-phone="{{ $distributor->phone }}">{{ $distributor->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-input-error :messages="$errors->get('distributor_id')" class="mt-2" />
                </div>

                <!-- Buyer Name -->
                {{-- <div class="mb-5 space-y-2">
                    <x-form.label for="buyer_name" :value="__('Nama Pembeli')" />
                    <x-form.input id="buyer_name" class="block w-full" type="text" name="buyer_name"
                        :value="old('buyer_name')" placeholder="{{ __('Nama Pembeli') }}" required autofocus />
                </div> --}}

                <!-- Buyer Address -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="buyer_address" :value="__('Alamat')" />
                    <x-form.input id="buyer_address" class="block w-full" type="text" name="buyer_address"
                        :value="old('buyer_address')" placeholder="{{ __('Alamat Pembeli') }}"  autofocus />
                </div>

                <!-- Buyer Phone -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="buyer_phone" :value="__('Nomor Telepon')" />
                    <x-form.input id="buyer_phone" class="block w-full" type="text" name="buyer_phone"
                        :value="old('buyer_phone')" placeholder="{{ __('Nomor Telepon Pembeli') }}"  autofocus />
                </div>

                <!-- Item -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="item_id" :value="__('Barang')" />
                    <x-form.select id="item_id" class="block w-full" name="item_id" required>
                        <option value="" disabled selected>{{ __('Pilih Barang') }}</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }}
                            </option>
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
                        <option value="lunas">{{ __('Lunas') }}</option>
                        <option value="belum lunas">{{ __('Belum Lunas') }}</option>
                    </x-form.select>
                    <x-input-error :messages="$errors->get('payment_status')" class="mt-2" />
                </div>

                <!-- Discount -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="discount" :value="__('Diskon (dalam angka)')" />
                    <x-form.input id="discount" class="block w-full" type="number" name="discount" :value="old('discount')"
                        placeholder="{{ __('Diskon (dalam angka)') }}" required />
                </div>

                <!-- Down Payment -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="down_payment" :value="__('Uang Muka')" />
                    <x-form.input id="down_payment" class="block w-full" type="number" name="down_payment"
                        :value="old('down_payment')" placeholder="{{ __('Uang Muka') }}" required />
                        <span id="formatted_down_payment" class="text-gray-500"></span>
                </div>

                <!-- Total Price -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="total_price" :value="__('Total Harga')" />
                    <x-form.input id="total_price" class="block w-full" type="text" name="total_price"
                        :value="old('total_price')" placeholder="{{ __('Total Harga') }}" required />
                        <span id="formatted_total_price" class="text-gray-500"></span>
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
            document.addEventListener("DOMContentLoaded", function() {
                const distributorSelect = document.getElementById("distributor_id");
                const buyerAddress = document.getElementById("buyer_address");
                const buyerPhone = document.getElementById("buyer_phone");

                const itemSelect = document.getElementById("item_id");
                const qtySold = document.getElementById("qty_sold");
                const discountInput = document.getElementById("discount");
                const downPayment = document.getElementById("down_payment");
                const totalPrice = document.getElementById("total_price");

                // Fungsi untuk mengonversi angka ke format Rupiah
                function formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(angka);
                }

                // Mengisi alamat dan nomor telepon berdasarkan distributor yang dipilih
                distributorSelect.addEventListener("change", function() {
                    const selectedOption = distributorSelect.options[distributorSelect.selectedIndex];

                    const address = selectedOption.getAttribute("data-address") || "";
                    const phone = selectedOption.getAttribute("data-phone") || "";

                    buyerAddress.value = address;
                    buyerPhone.value = phone;
                });

                function calculateTotalPrice() {
                    const selectedItem = itemSelect.options[itemSelect.selectedIndex];
                    const price = parseFloat(selectedItem.getAttribute("data-price")) || 0;
                    const qty = parseInt(qtySold.value) || 0;
                    const discount = parseFloat(discountInput.value) || 0;

                    let total = price * qty;

                    if (discount > 0) {
                        total -= (total * (discount / 100));
                    }

                    totalPrice.value = total;
                    totalPrice.nextElementSibling.textContent = formatRupiah(total);
                }

                function formatDownPayment() {
                    const value = parseFloat(downPayment.value) || 0;
                    downPayment.nextElementSibling.textContent = formatRupiah(value);
                }

                // Event listeners
                itemSelect.addEventListener("change", calculateTotalPrice);
                qtySold.addEventListener("input", calculateTotalPrice);
                discountInput.addEventListener("input", calculateTotalPrice);
                downPayment.addEventListener("input", formatDownPayment);
            });
        </script>
    @endpush

</x-app-layout>
