<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.distributors.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah distributor') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.distributors.store') }}" method="POST">
            @csrf

            <div class="grid gap-2">
                <div class="mb-5 space-y-2">
                    <x-form.label for="name" :value="__('Nama distributor')" />
                    <x-form.input id="name" class="block w-full" type="text" name="name" :value="old('name')"
                        placeholder="{{ __('Nama distributor') }}" required />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="address" :value="__('Alamat')" />
                    <x-form.input id="address" class="block w-full" type="text" name="address" :value="old('address')"
                        placeholder="{{ __('Alamat') }}" required />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="phone" :value="__('Nomor telepon')" />
                    <x-form.input id="phone" class="block w-full" type="number" name="phone" :value="old('phone')"
                        placeholder="{{ __('086751324897') }}" required />
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
