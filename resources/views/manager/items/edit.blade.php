<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button href="{{ route('manager.items.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">{{ __('Edit Barang') }}</h2>
        </div>
    </x-slot>

    <div class="mt-6">
        <h3 class="text-lg font-semibold">{{ __('Barang') }}</h3>
        <hr class="my-2 border-gray-300">
    </div>

    <form action="{{ route('manager.items.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-form.label for="name" :value="__('Nama Barang')" />
                    <x-form.input id="name" class="w-full" type="text" name="name"
                        value="{{ old('name', $item->name) }}" placeholder="Nama Barang" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />

                    <x-form.label for="code" :value="__('Kode Barang')" />
                    <x-form.input id="code" class="w-full" type="text" name="code"
                        value="{{ old('code', $item->code) }}" disabled />

                    <x-form.label for="unit" :value="__('Satuan')" />
                    <x-form.input id="unit" class="block w-full" type="text" name="unit"
                        value="{{ old('unit', $item->unit) }}" placeholder="Satuan (pcs, kg, liter, dll)" />
                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />

                    <x-form.label for="description" :value="__('Deskripsi')" />
                    <textarea id="description" name="description" class="w-full border-gray-400 rounded-md" rows="3"
                        placeholder="Deskripsi barang">{{ old('description', $item->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="space-y-2">
                    <x-form.label for="purchase_price" :value="__('Harga Beli')" />
                    <x-form.input id="purchase_price" class="w-full" type="text" step=".01" name="purchase_price"
                        :value="old('purchase_price', number_format($item->purchase_price, 2, ',', '.') )" placeholder="Harga Beli" required
                        data-parsley-required-message="Harga beli barang wajib diisi" />
                    <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />

                    <x-form.label for="warehouse_id" :value="__('Gudang')" />
                    <x-form.select id="warehouse_id" class="block w-full" name="warehouse_id">
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}"
                                {{ $item->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />

                    <x-form.label for="category_id" :value="__('Kategori')" />
                    <x-form.select id="category_id" class="block w-full" name="category_id">
                        <option value="" disabled selected>Pilih</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $item->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}
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

            <button type="button" id="addSupplierRow" class="mt-2 px-4 py-2 bg-purple-500 text-white rounded">+ Tambah
                Supplier</button>
            <table class="w-full border border-gray-300" id="supplierTable">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 text-left">Supplier</th>
                        <th class="p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->suppliers as $supplier)
                        <tr>
                            <td class="p-2">
                                <select name="suppliers[]" class="w-full p-2 border border-gray-300 rounded">
                                    @foreach ($suppliers as $sup)
                                        <option value="{{ $sup->id }}"
                                            {{ $supplier->id == $sup->id ? 'selected' : '' }}>{{ $sup->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-2 text-center">
                                <button type="button"
                                    class="removeSupplierRow px-2 py-1 bg-red-500 text-white rounded">X</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid justify-items-end mt-6">
            <x-button class="gap-2" id="buttonSubmit">Update</x-button>
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
                            <button type="button" class="removeSupplierRow px-2 py-1 bg-red-500 text-white rounded">X</button>
                        </td>
                    </tr>`;
                    $('#supplierTable tbody').append(row);
                });

                $(document).on('click', '.removeSupplierRow', function() {
                    $(this).closest('tr').remove();
                });

                $('#purchase_price').on('input', function(e) {
                    let value = e.target.value.replace(/[^,\d]/g, '').toString();

                    let split = value.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/g);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    if (split[1] !== undefined) {
                        rupiah += ',' + split[1].slice(0, 2);
                    }

                    $(this).val(rupiah);
                });

                $('#purchase_price').on('input', function() {
                    let purchasePrice = parseFloat($(this).val().replace(/\./g, '').replace(',', '.')) || 0;

                    purchasePrice = parseFloat(purchasePrice.toFixed(2));
                });
            });
        </script>
    @endpush
</x-app-layout>
