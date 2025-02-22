<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">{{ __('Tambah Data Pembelian') }}</h2>
    </x-slot>

    <form action="{{ route('manager.purchase.store') }}" method="POST">
        @csrf
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-form.label for="purchase_number" :value="__('Nomor Pembelian')" />
                    <x-form.input id="purchase_number" class="block w-full" type="text" name="purchase_number" />

                    <x-form.label for="purchase_date" :value="__('Tanggal Pembelian')" />
                    <x-form.input id="purchase_date" class="block w-full flatpickr-input" type="date"
                        name="purchase_date" />

                    <x-form.label for="supplier_id" :value="__('Supplier')" />
                    <select id="supplier_id" name="supplier_id" class="w-full select2">
                        <option value="" selected disabled>Pilih</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>

                    <x-form.label for="tax" :value="__('Pajak')" />
                    <x-form.select id="tax" class="block w-full" name="tax">
                        <option value="" disabled selected>Pilih</option>
                        <option value="ppn" {{ old('tax') == 'ppn' ? 'selected' : '' }}>PPN 11%</option>
                        <option value="non_ppn" {{ old('tax') == 'non_ppn' ? 'selected' : '' }}>NON-PPN</option>
                    </x-form.select>
                </div>

                <div>
                    <x-form.label for="information" :value="__('Keterangan')" />
                    <textarea id="information" name="information"
                        class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                        rows="3" placeholder="Deskripsi barang">{{ old('information') }}</textarea>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <h3 class="text-lg font-semibold">{{ __('Barang') }}</h3>
            <hr class="my-2 border-gray-300">
        </div>

        <div class="mt-5 space-y-2">
            <p id="supplier_null" class="text-red-500 mt-2"></p>

            <button type="button" id="add-item" class="mt-2 px-4 py-2 bg-purple-500 text-white rounded">+ Tambah
                Barang
            </button>

            <table class="w-full border border-gray-300 mt-2 shadow-md rounded-lg overflow-hidden" id="items-table">
                <thead class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider">
                    <tr>
                        <th class="px-4 py-2 text-left border-b border-gray-300 w-2/12">Kode Barang</th>
                        <th class="px-4 py-2 text-left border-b border-gray-300 w-3/12">Nama Barang</th>
                        <th class="px-2 py-2 text-center border-b border-gray-300 w-1/12">Stok</th>
                        <th class="px-2 py-2 text-center border-b border-gray-300 w-1/12">Satuan</th>
                        <th class="px-3 py-2 text-center border-b border-gray-300 w-1/12">Jumlah</th>
                        <th class="px-4 py-2 text-right border-b border-gray-300 w-2/12">Harga/pcs</th>
                        <th class="px-4 py-2 text-right border-b border-gray-300 w-2/12">Diskon (%)</th>
                        <th class="px-4 py-2 text-right border-b border-gray-300 w-2/12">Total Harga</th>
                        <th class="px-3 py-2 text-center border-b border-gray-300 w-1/12">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300">
                    @foreach ($purchase->items as $index => $item)
                        <tr>
                            <td><input type="text"
                                    class="item-code w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100"
                                    value="{{ $item->code }}" readonly></td>
                            <td>
                                <select name="items[]"
                                    class="item-select w-full select2 px-2 py-1 border border-gray-300 rounded-md">
                                    <option value="">Pilih Barang</option>
                                    @foreach ($items as $availableItem)
                                        <option value="{{ $availableItem->id }}"
                                            {{ $item->id == $availableItem->id ? 'selected' : '' }}>
                                            {{ $availableItem->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="stock[]"
                                    class="stock w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100"
                                    readonly value="{{ $item->stock }}"></td>
                            <td><input type="text" name="unit[]"
                                    class="unit w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100" readonly
                                    value="{{ $item->unit }}"></td>
                            <td><input type="number" name="qty[]"
                                    class="qty w-full px-2 py-1 border border-gray-300 rounded-md text-center"
                                    value="{{ $item->pivot->qty }}"></td>

                            <td><input type="text" name="prices[]"
                                    class="price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                    value="{{ $item->purchase_price }}" readonly></td>
                            <td>
                                <div class="grid grid-cols-2 gap-1">
                                    <input type="text" name="discount1[]"
                                        class="discount1 w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                        value="{{ $purchase->supplier->discount1 ?? '' }}" readonly>

                                    <input type="text" name="discount2[]"
                                        class="discount2 w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                        value="{{ $purchase->supplier->discount2 ?? '' }}" readonly>
                                </div>
                            </td>

                            <td><input type="text" name="total_prices[]"
                                    class="total-price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                    readonly value="{{ number_format($item->pivot->price_per_item, 2, '.', '') }}">
                            </td>
                            <td><button type="button"
                                    class="remove-item px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>

        <div class="grid justify-items-end mt-4 space-y-2">
            <div class="flex justify-between items-center w-full max-w-md">
                <label for="sub_total" class="mr-4">Sub Total</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="sub_total" id="sub_total"
                    readonly value="{{ number_format($purchase->sub_total, 0, ',', '.') }}">
            </div>

            {{-- <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_discount" class="mr-4">Diskon</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_discount"
                    id="total_discount" readonly >
            </div> --}}

            {{-- <div class="flex justify-between items-center w-full max-w-md">
                <label for="tax" class="mr-4">PPN 11%</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="tax" id="taxRate"
                    readonly>
            </div> --}}

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_price" class="mr-4">Total Price</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_price"
                    id="total_price" readonly value="{{ number_format($purchase->total_price, 0, ',', '.') }}">
            </div>
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
                $("#purchase_date").flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    minDate: "today"
                });

                $('#supplier_id').select2();

                $('#supplier_id').on('change', function() {
                    let supplierId = $(this).val();
                    $('.item-select').each(function() {
                        let row = $(this).closest('tr');
                        let itemId = $(this).val();
                        updateItemData(row, itemId, supplierId);
                    });
                });

                $(document).on('change', '.item-select', function() {
                    let row = $(this).closest('tr');
                    let itemId = $(this).val();
                    let supplierId = $('#supplier_id').val();
                    updateItemData(row, itemId, supplierId);
                });

                function updateItemData(row, itemId, supplierId) {
                    if (itemId && supplierId) {
                        $.get(`/get-item/${itemId}/${supplierId}`, function(data) {
                            row.find('.item-code').val(data.code);
                            row.find('.item-name').val(data.name);
                            row.find('.price').val(data.purchase_price);
                            row.find('.unit').val(data.unit);
                            row.find('.stock').val(data.stock);
                            row.find('.discount1').val(data.discount1);
                            row.find('.discount2').val(data.discount2);
                            row.find('.qty').val();
                            calculateTotal(row);
                        });
                    }
                }

                $(document).on('input', '.qty', function() {
                    let row = $(this).closest('tr');
                    calculateTotal(row);
                });

                function calculateTotal(row) {
                    let price = parseFloat(row.find('.price').val()) || 0;
                    let qty = parseFloat(row.find('.qty').val()) || 0;
                    let discount1 = parseFloat(row.find('.discount1').val()) || 0;
                    let discount2 = parseFloat(row.find('.discount2').val()) || 0;

                    let total = (price - (discount1 + discount2)) * qty;
                    row.find('.total-price').val(total.toFixed(2));

                    calculateSubTotal();
                }

                function calculateSubTotal() {
                    let subTotal = 0;
                    let totalDiscount = 0;

                    $('.total-price').each(function() {
                        subTotal += parseFloat($(this).val()) || 0;
                    });

                    $('.discount1, .discount2').each(function() {
                        totalDiscount += parseFloat($(this).val()) || 0;
                    });

                    $('#sub_total').val(subTotal.toFixed(2));
                    $('#total_discount').val(totalDiscount.toFixed(2));

                    calculateTotalPrice();
                }

                function calculateTotalPrice() {
                    let subTotal = parseFloat($('#sub_total').val()) || 0;
                    let taxRate = $('#tax').val() === 'ppn' ? 0.11 : 0;
                    let taxAmount = subTotal * taxRate;
                    let totalPrice = subTotal - taxAmount;

                    console.log(`Tax Rate: ${taxRate}, Tax Amount: ${taxAmount}, Final Total: ${totalPrice}`);

                    $('#taxRate').val(taxAmount.toFixed(2));
                    $('#total_price').val(totalPrice.toFixed(2));
                }

                $('#tax').on('change', function() {
                    calculateTotalPrice();
                });

                $('#add-item').click(function() {
                    let supplierId = $('#supplier_id').val();
                    $('#supplier_null').text(!supplierId ? 'Silakan pilih supplier terlebih dahulu!' : '');
                    if (!supplierId) return;

                    let row = `
                <tr>
                    <td><input type="text" class="item-code w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100" readonly></td>
                    <td>
                        <select name="items[]" class="item-select w-full select2 px-2 py-1 border border-gray-300 rounded-md">
                            <option value="">Pilih Barang</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="stock[]" class="stock w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100" readonly></td>
                    <td><input type="text" name="unit[]" class="unit w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100" readonly></td>
                    <td><input type="number" name="qty[]" class="qty w-full px-2 py-1 border border-gray-300 rounded-md text-center" min="1" value="1"></td>
                    <td><input type="text" name="prices[]" class="price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly></td>
                    <td>
                        <div class="grid grid-cols-2 gap-1">
                            <input type="text" name="discount1[]" class="discount1 w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly>
                            <input type="text" name="discount2[]" class="discount2 w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly>
                        </div>
                    </td>
                    <td><input type="text" name="total_prices[]" class="total-price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly></td>
                    <td><button type="button" class="remove-item px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">Hapus</button></td>
                </tr>
                `;

                    $('#items-table tbody').append(row);
                    $('.item-select').select2();
                });

                $(document).on('click', '.remove-item', function() {
                    $(this).closest('tr').remove();
                    calculateSubTotal();
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .select2-container .select2-selection--single {
                height: 37px !important;
                border-radius: 5px;
                border: 1px solid #9CA3AF;
                padding-left: 0.30rem;
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
            }

            .select2-container .select2-selection--single .select2-selection__rendered {
                font-size: 16px;
                color: #374151;
            }

            .select2-container .select2-selection--single .select2-selection__arrow {
                height: 37px !important;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #3b82f6 !important;
                color: white !important;
            }

            .select2-container--default .select2-results__option {
                font-size: 14px;
                padding: 10px;
            }
        </style>
    @endpush
</x-app-layout>
