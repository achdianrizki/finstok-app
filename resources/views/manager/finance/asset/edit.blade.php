<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.asset.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah Aset') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.asset.update', $asset->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid gap-2">
                <div class="mb-5 space-y-2">
                    <x-form.label for="name" :value="__('Nama barang')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name" :value="old('name', $asset->name)"
                        placeholder="{{ __('Nama barang') }}" required />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="price" :value="__('Harga (PCS)')" />
                    <x-form.input id="price" class="block w-full" type="number" name="price" :value="old('price', $asset->price)"
                        placeholder="{{ __('Harga per PCS') }}" readonly />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="qty" :value="__('Jumlah barang')" />
                    <x-form.input id="qty" class="block w-full" type="number" name="qty" :value="old('qty', $asset->qty)"
                        placeholder="{{ __('Jumlah barang') }}" required />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="total_price" :value="__('Harga total')" />
                    <x-form.input id="total_price" class="block w-full" type="text" name="total_price"
                        :value="old('total_price', $asset->total_price)" placeholder="{{ __('Harga total') }}" readonly />
                    <input type="hidden" id="hidden_total_price" name="total_price" />
                </div>

                <div class="grid justify-items-end">
                    <x-button class="gap-2">
                        <span>{{ __('Submit') }}</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const priceInput = document.getElementById('price');
        const qtyInput = document.getElementById('qty');
        const totalPriceInput = document.getElementById('total_price');
        const hiddenTotalPriceInput = document.getElementById('hidden_total_price');

        // Fungsi untuk menghitung harga total
        function calculateTotalPrice() {
            const price = parseFloat(priceInput.value) || 0;
            const qty = parseInt(qtyInput.value) || 0;
            const totalPrice = price * qty;

            // Menggunakan toLocaleString untuk menampilkan sebagai IDR
            totalPriceInput.value = totalPrice.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            });

            // Menyimpan nilai totalPrice tanpa format ke input tersembunyi untuk dikirim ke server
            hiddenTotalPriceInput.value = totalPrice.toFixed(2);
        }

        // Menambahkan event listener untuk menghitung ulang harga total ketika nilai berubah
        priceInput.addEventListener('input', calculateTotalPrice);
        qtyInput.addEventListener('input', calculateTotalPrice);
    </script>

</x-app-layout>
