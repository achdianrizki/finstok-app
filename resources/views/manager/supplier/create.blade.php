<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.users.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah Pemasok') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.supplier.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <x-form.label for="supplier_code" :value="__('Kode Pemasok')" />
                <x-form.input id="supplier_code" class="block w-full" type="text" name="supplier_code"
                :value="old('supplier_code')" placeholder="{{ __('Masukan Kode Pemasok') }}" />
                @error('supplier_code')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-form.label for="name" :value="__('Nama Pemasok')" />
                <x-form.input id="name" class="block w-full" type="text" name="name" :value="old('name')"
                placeholder="{{ __('Masukan Nama Pemasok') }}" />
                @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-form.label for="contact" :value="__('Kontak')" />
                <x-form.input id="contact" class="block w-full" type="text" name="contact" :value="old('contact')"
                placeholder="{{ __('Masukan Kontak') }}" />
                @error('contact')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-form.label for="phone" :value="__('No Tlp')" />
                <x-form.input id="phone" class="block w-full" type="text" name="phone" :value="old('phone')"
                placeholder="{{ __('Nomor Telepon') }}" />
                @error('phone')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-form.label for="address" :value="__('Alamat')" />
                <textarea name="address" id="address" class="block w-full py-2 border-gray-400 rounded-md">{{ old('address') }}</textarea>
                @error('address')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-2">
                <x-form.label for="fax_nomor" :value="__('Nomor Fax')" />
                <x-form.input id="fax_nomor" class="block w-full" type="text" name="fax_nomor" :value="old('fax_nomor')"
                placeholder="{{ __('Masukan Nomor Fax') }}" />
                @error('fax_nomor')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-form.label for="city" :value="__('Kota')" />
                <x-form.input id="city" class="block w-full" type="text" name="city" :value="old('city')"
                placeholder="{{ __('Masukan Kota') }}" />
                @error('city')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-form.label for="province" :value="__('Provinsi')" />
                <x-form.input id="province" class="block w-full" type="text" name="province" :value="old('province')"
                placeholder="{{ __('Masukan Provinsi') }}" />
                @error('province')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-form.label for="payment_term" :value="__('Jangka Waktu Pembayaran')" />
                <x-form.input id="payment_term" class="block w-full" type="text" name="payment_term"
                :value="old('payment_term')" placeholder="{{ __('Masukan Jangka Waktu Pembayaran') }}" />
                @error('payment_term')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <x-form.label for="status" :value="__('Status')" />
                <select id="status" name="status" class="block w-full py-2 border-gray-400 rounded-md">
                <option value="1">{{ __('Aktif') }}</option>
                <option value="0">{{ __('Tidak Aktif') }}</option>
                </select>
                @error('status')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            </div>

            <div class="grid justify-items-end mt-4">
            <x-button class="gap-2">
                <span>{{ __('Submit') }}</span>
            </x-button>
            </div>
        </form>
    </div>

</x-app-layout>
