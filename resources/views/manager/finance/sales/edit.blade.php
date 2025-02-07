<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.sales.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Edit Penjualan') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.sales.update', $sale->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid gap-6">

                <!-- Uang Masuk -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="down_payment" :value="__('Uang Masuk')" />
                    <x-form.input id="down_payment" class="block w-full" type="text" name="down_payment"
                        value="Rp {{ number_format(old('down_payment', $sale->down_payment), 0, ',', '.') }}"
                        placeholder="{{ __('') }}" readonly />
                </div>

                <!-- Sisa Tagihan -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="remaining_payment" :value="__('Sisa Tagihan')" />
                    <x-form.input id="remaining_payment" class="block w-full" type="text" name="remaining_payment"
                        value="Rp {{ number_format(old('remaining_payment', $sale->remaining_payment), 0, ',', '.') }}"
                        placeholder="{{ __('') }}" readonly />
                </div>

                <!-- Total Harga -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="total_price" :value="__('Total Harga')" />
                    <x-form.input id="total_price" class="block w-full" type="text" name="total_price"
                        value="Rp {{ number_format(old('total_price', $sale->total_price), 0, ',', '.') }}"
                        placeholder="{{ __('Total Harga') }}" readonly />
                </div>

                <!-- Bayar -->
                <div class="mb-5 space-y-2">
                    <x-form.label for="update_payment" :value="__('Bayar')" />
                    <x-form.input id="update_payment" class="block w-full" type="number" name="update_payment"
                        value="{{ old('update_payment') }}" placeholder="{{ __('Pembayaran') }}" required />
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

</x-app-layout>
