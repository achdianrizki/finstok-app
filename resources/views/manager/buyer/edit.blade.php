<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.buyer.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Edit pelanggan') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.buyer.update', $buyer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid gap-2">
                <div class="mb-5 space-y-2">
                    <x-form.label for="name" :value="__('Nama pelanggan')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name" :value="old('name', $buyer->name)"
                        placeholder="{{ __('Nama pelanggan') }}" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="phone" :value="__('Nomor telepon')" />
                    <x-form.input id="phone" class="block w-full" type="number" name="phone" :value="old('phone', $buyer->phone)"
                        placeholder="{{ __('086751324897') }}" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="address" :value="__('Alamat')" />
                    <x-form.input id="address" class="block w-full" type="text" name="address" :value="old('address', $buyer->address)"
                        placeholder="{{ __('Alamat') }}" />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="type" :value="__('Tipe pelanggan')" />
                    <x-form.select id="type" class="block w-full" name="type">
                        <option value="" disabled {{ old('type', $buyer->type) == '' ? 'selected' : '' }}>Pilih
                            Tipe Pelanggan...</option>
                        <option value="General Trade"
                            {{ old('type', $buyer->type) == 'General Trade' ? 'selected' : '' }}>General Trade</option>
                        <option value="Modern Trade"
                            {{ old('type', $buyer->type) == 'Modern Trade' ? 'selected' : '' }}>Modern Trade</option>
                    </x-form.select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <div class="grid justify-items-end">
                    <x-button class="gap-2">
                        <span>{{ __('Submit') }}</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>

</x-app-layout>
