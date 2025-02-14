<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.items.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah Barang') }}
            </h2>
        </div>
    </x-slot>


    <div class="mt-6">
        <h3 class="text-lg font-semibold">{{ __(' Barang') }}</h3>
        <hr class="my-2 border-gray-300">
    </div>

    <form action="{{ route('manager.items.store') }}" method="POST">
        @csrf
        <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-form.label for="name" :value="__('Nama Barang')" />
                    <x-form.input id="name" class="w-full" type="text" name="name" :value="old('name')"
                        placeholder="Nama Barang" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />

                    <x-form.label for="code" :value="__('Kode Barang')" />
                    <x-form.input id="code" class="w-full" type="text" name="code" :value="old('code')"
                        placeholder="Kode Barang" />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />


                    <x-form.label for="unit" :value="__('Satuan')" />
                    <x-form.input id="unit" class="block w-full" type="text" name="unit" :value="old('unit')"
                        placeholder="Satuan (pcs, kg, liter, dll)" />
                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />

                    <x-form.label for="description" :value="__('Deskripsi')" />
                    <textarea id="description" name="description"
                        class="w-full border-gray-400 rounded-md focus:border-gray-400 focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                        rows="3" placeholder="Deskripsi barang">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />

                </div>

                <div class="space-y-2">
                    <x-form.label for="purchase_price" :value="__('Harga Beli')" />
                    <x-form.input id="purchase_price" class="w-full" type="number" name="purchase_price"
                        :value="old('purchase_price')" placeholder="Harga Beli" />
                    <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />

                    <x-form.label for="selling_price" :value="__('Harga Jual')" />
                    <x-form.input id="selling_price" class="w-full" type="number" name="selling_price"
                        :value="old('selling_price')" placeholder="Harga Jual" />
                    <x-input-error :messages="$errors->get('selling_price')" class="mt-2" />

                    <x-form.label for="warehouse_id" :value="__('Gudang')" />
                    <x-form.select id="warehouse_id" class="block w-full" name="warehouse_id">
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}"
                                {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />

                    <x-form.label for="category_id" :value="__('Kategori')" />
                    <x-form.select id="category_id" class="block w-full" name="category_id">
                        <option value="" disabled selected>Pilih</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>
            </div>
        </div>


        <div class="mt-6">
            <h3 class="text-lg font-semibold">{{ __('Penyedia Barang') }}</h3>
            <hr class="my-2 border-gray-300">
        </div>

        <div class="mt-5 space-y-2">
            <x-input-error :messages="$errors->get('suppliers')" class="mt-2" />

            <button type="button" id="addSupplierRow" class="mt-2 px-4 py-2 bg-purple-500 text-white rounded">
                + Tambah Supplier
            </button>

            <table class="w-full border border-gray-300" id="supplierTable">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 text-left">Supplier</th>
                        <th class="p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>


        </div>

        <div class="grid justify-items-end">
            <x-button class="gap-2" id="buttonSubmit">
                <span>{{ __('Submit') }}</span>
            </x-button>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let suppliers = @json($suppliers);
                let supplierOptions = '';

                suppliers.forEach(supplier => {
                    supplierOptions += `<option value="${supplier.id}">${supplier.name}</option>`;
                });

                $('#addSupplierRow').click(function() {
                    let row = `
                    <tr>
                        <td class="p-2">
                            <select name="suppliers[]" class="w-full p-2 border border-gray-300 rounded">
                                <option value="">Pilih Supplier</option>
                                ${supplierOptions}
                            </select>
                        </td>
                        <td class="p-2 text-center">
                            <button type="button" class="removeSupplierRow px-2 py-1 bg-red-500 text-white rounded">
                                X
                            </button>
                        </td>
                    </tr>
                `;
                    $('#supplierTable tbody').append(row);
                });

                $(document).on('click', '.removeSupplierRow', function() {
                    $(this).closest('tr').remove();
                });
            });
        </script>
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
