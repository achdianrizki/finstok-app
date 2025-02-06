<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.items.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah barang') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.items.store') }}" method="POST">
            @csrf
            <div class="grid gap-2">
                <div class="flex flex-col md:flex-row ">
                    <div class="w-full md:w-1/2 mb-5 space-y-2">
                        <x-form.label for="name" :value="__('Nama Barang')" />
                        <x-form.input id="name" class="w-full" type="text" name="name" :value="old('name')"
                            placeholder="{{ __('Nama Barang') }}" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="w-full md:w-1/2 md:ml-3 md:mt-0 mb-5 space-y-2">
                        <x-form.label for="code" :value="__('Kode Barang')" />
                        <x-form.input id="code" class="w-full" type="text" name="code" :value="old('code')"
                            placeholder="{{ __('Kode Barang') }}" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="price" :value="__('Harga/PCS')" />
                    <x-form.input id="price" class="block w-full" type="number" inputmode="numeric" name="price"
                        :value="old('price')" placeholder="{{ __('Harga') }}" />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="stock" :value="__('Stock Awal')" />
                    <x-form.input id="stock" class="block w-full" type="number" inputmode="numeric" name="stock"
                        :value="old('stock')" placeholder="{{ __('stock awal') }}" />
                    <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="category" :value="__('Kategori')" />
                    <div class="custom-select">
                        <input type="text" id="selectInput" name="category_name"
                            class="block w-full py-2 border-gray-400 rounded-md focus:border-gray-400 focus:ring
                        focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-white dark:border-gray-600 dark:bg-dark-eval-1
                        dark:text-gray-300 dark:focus:ring-offset-dark-eval-1"
                            placeholder="Pilih atau ketik..." autocomplete="off">
                        <input type="hidden" id="category_id" name="category_id">
                        <ul id="selectOptions"></ul>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="warehouse" :value="__('Gudang')" />
                    <x-form.select id="warehouse" class="block w-full" name="warehouse_id">
                        @forelse ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @empty
                            <option value="" selected disabled hidden>{{ __('Pilih Gudang') }}</option>
                        @endforelse
                    </x-form.select>
                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
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
        @include('components.js.selectOrCreate')
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
