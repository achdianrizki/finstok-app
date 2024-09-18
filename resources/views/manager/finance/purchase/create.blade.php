<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.items.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah Data Pembelian') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.purchase.store') }}" method="POST">
            @csrf
            <div class="grid gap-2">

                <div class="mb-5 space-y-2">
                    <x-form.label for="name" :value="__('Nama barang')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name" :value="old('name')"
                        placeholder="{{ __('Nama Barang') }}" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="price" :value="__('Harga/PCS')" />
                    <x-form.input id="price" class="block w-full" type="number" inputmode="numeric" name="price"
                        :value="old('price')" placeholder="{{ __('Harga') }}" />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="qty" :value="__('Jumlah')" />
                    <x-form.input id="qty" class="block w-full" type="number" inputmode="numeric" name="qty"
                        :value="old('qty')" placeholder="{{ __('Jumlah Barang') }}" />
                    <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="total_price" :value="__('Total')" />
                    <x-form.input id="total_price" class="block w-full" type="text"
                        name="total_price" :value="old('total_price')" placeholder="{{ __('Total') }}" readonly />
                    <x-input-error :messages="$errors->get('total_price')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="supplier_name" :value="__('supplier_name')" />
                    <x-form.input id="supplier_name" class="block w-full" type="text" name="supplier_name"
                        :value="old('supplier_name')" placeholder="{{ __('Nama Supplier') }}" />
                    <x-input-error :messages="$errors->get('supplier_name')" class="mt-2" />
                </div>

                <div class="grid justify-items-end">
                    <x-button class="gap-2" id="buttonSubmit">
                        <span>{{ __('Submit') }}</span>
                    </x-button>
                </div>

            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const itemQuantity = document.getElementById('qty');
            const itemPrice = document.getElementById('price');
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

    @push('styles')
        <style>
            .custom-select input {
                width: 100%;
                padding: 8px;
                box-sizing: border-box;
                border-radius: 5px;
            }

            .custom-select ul {
                position: absolute;
                max-height: 150px;
                overflow-y: auto;
                background-color: white;
                border: 1px solid #ccc;
                margin-top: 0;
                padding-left: 0;
                list-style-type: none;
                display: none;
                border-radius: 5px;
                font-size: 10px;
            }

            .custom-select ul li {
                padding: 8px;
                cursor: pointer;
            }

            .custom-select ul li:hover {
                background-color: #f1f1f1;
            }
        </style>
    @endpush
</x-app-layout>
