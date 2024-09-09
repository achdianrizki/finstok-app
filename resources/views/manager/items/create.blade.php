<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.items.index') }}" variant="primary" size="sm" class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah barang') }}
            </h2>
        </div>
    </x-slot>    

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="" method="POST">
            @csrf

            <div class="grid gap-2">
                <div class="flex flex-col md:flex-row ">
                    <div class="w-full md:w-1/2 mb-5 space-y-2">
                        <x-form.label for="name" :value="__('Nama Barang')" />
                        <x-form.input id="name" class="w-full" type="text" name="name"
                            :value="old('name')" placeholder="{{ __('Nama Barang') }}" required />
                    </div>
            
                    <div class="w-full md:w-1/2 md:ml-3 md:mt-0 mb-5 space-y-2">
                        <x-form.label for="code" :value="__('Kode Barang')" />
                        <x-form.input id="code" class="w-full" type="text" name="code"
                            :value="old('code')" placeholder="{{ __('Kode Barang') }}" required />
                    </div>
                </div>
                
                <div class="mb-5 space-y-2">
                    <x-form.label for="Category" :value="__('Kategori')" />
                    <x-form.select id="category" class="block w-full" type="text" name="id_category"
                        :value="old('id_category')" placeholder="{{ __('Kategori') }}" required>
                        @forelse ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @empty
                            <p>Belum ada Kategori saat ini</p>
                        @endforelse
                    </x-form.select>
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="price" :value="__('Harga/PCS')" />
                    <x-form.input id="price" class="block w-full" type="number" inputmode="numeric" name="price" :value="old('price')"
                        placeholder="{{ __('Harga') }}" required />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="stok" :value="__('Stok awal')" />
                    <x-form.input id="stok" class="block w-full" type="text" name="stok" :value="old('stok')"
                        placeholder="{{ __('Stok awal') }}" required />
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
