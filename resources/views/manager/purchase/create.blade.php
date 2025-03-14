<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">{{ __('Tambah Data Pembelian') }}</h2>
    </x-slot>

    <form id="purchase-form" action="{{ route('manager.purchase.store') }}" method="POST" data-parsley-validate>
        @csrf
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-form.label for="purchase_date" :value="__('Tanggal Pembelian')" />
                    <x-form.input id="purchase_date" class="block w-full flatpickr-input" type="date"
                        name="purchase_date" autocomplete="off" required
                        data-parsley-required-message="Tanggal Penjualan wajib diisi" />

                    <x-form.label for="supplier_id" :value="__('Supplier')" />
                    <select id="supplier_id" name="supplier_id" class="w-full select2">
                        <option value="" selected disabled>Pilih</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->contact }}</option>
                        @endforeach
                    </select>

                    <x-form.label for="tax" :value="__('Pajak')" />
                    <x-form.select id="tax" class="block w-full" name="tax_type">
                        <option value="" disabled selected>Pilih</option>
                        <option value="ppn" {{ old('tax_type') == 'ppn' ? 'selected' : '' }}>PPN 11%</option>
                        <option value="non_ppn" {{ old('tax_type') == 'non_ppn' ? 'selected' : '' }}>NON-PPN</option>
                    </x-form.select>
                </div>

                <div class="space-y-2">
                    <x-form.label for="information" :value="__('Keterangan')" />
                    <textarea id="information" name="information"
                        class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                        rows="3" placeholder="Deskripsi barang">{{ old('information') }}</textarea>

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
                </div>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold">{{ __('Barang') }}</h3>
            <hr class="my-2 border-gray-300">
        </div>

        <div class="mt-5 space-y-2">
            <p id="supplier_null" class="text-red-500 mt-2"></p>
            <p id="tax_null" class="text-red-500 mt-2"></p>

            <button type="button" id="add-item" class="mt-2 px-4 py-2 bg-purple-500 text-white rounded">
                + Tambah Barang
            </button>

            <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-lg shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max border border-gray-300 shadow-md table-auto" id="items-table">
                        <thead class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider sticky top-0 z-10">
                            <tr>
                                <th class="px-1 py-1 text-center border-b border-gray-300 ">Kode Barang</th>
                                <th class="px-1 py-1 text-center border-b border-gray-300 ">Nama Barang</th>
                                <th class="px-2 py-2 text-center border-b border-gray-300 ">Stok</th>
                                <th class="px-2 py-2 text-center border-b border-gray-300 ">Satuan</th>
                                <th class="px-3 py-2 text-center border-b border-gray-300 ">Jumlah</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Harga/pcs</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Diskon (%)</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Total Harga</th>
                                <th class="px-3 py-2 text-center border-b border-gray-300 ">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-300">
                            <!-- Data Barang akan ditambahkan di sini -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="grid justify-items-end mt-4 space-y-2">
            <div class="flex justify-between items-center w-full max-w-md">
                <label for="sub_total" class="mr-4">Sub Total</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="sub_total" id="sub_total"
                    readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_discount1" class="mr-4">Diskon 1</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_discount1"
                    id="total_discount1" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_discount2" class="mr-4">Diskon 2</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_discount2"
                    id="total_discount2" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_discount3" class="mr-4">Diskon 3</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_discount3"
                    id="total_discount3" readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="tax" class="mr-4">PPN 11%</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="tax" id="taxRate"
                    readonly>
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_price" class="mr-4">Total Price</label>
                <input type="text" class="w-1/2 border-gray-300 rounded-md p-2" name="total_price"
                    id="total_price" readonly>
            </div>
        </div>

        <div class="grid justify-items-end mt-4 space-y-2">
            <div class="flex justify-between items-center w-full max-w-md">
                <label for="status" class="mr-4">Status Pembayaran</label>
                <select name="status" id="status" class="w-1/2 border-gray-300 rounded-md p-2">
                    <option value="belum_lunas" selected>Belum Lunas</option>
                    <option value="lunas">Lunas</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="total_qty" id="total_qty">

        <div class="grid justify-items-end">
            <x-button class="gap-2" id="buttonSubmit">
                <span>{{ __('Submit') }}</span>
            </x-button>
        </div>
    </form>

    @push('scripts')
        <script>
            $('#buttonSubmit').click(function(event) {
                const items = document.querySelectorAll('.item-select');

                $('#error-message').remove();

                if (items.length === 0) {
                    event.preventDefault();

                    $('#add-item').after(
                        '<p id="error-message" class="text-red-500 mt-2">Barang tidak boleh kosong</p>');
                }
            });

            $(document).ready(function() {
                $("#purchase_date").flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
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
                    row.find('.qty').val(1); // Set default qty to 1
                    updateItemData(row, itemId, supplierId);
                });

                function updateItemData(row, itemId, supplierId) {
                    if (itemId && supplierId) {
                        $.get(`/get-item/${itemId}/${supplierId}`, function(data) {
                            row.find('.item-code').val(data.code);
                            row.find('.item-name').val(data.name);
                            row.find('.price')
                                .val(formatRupiah(data.purchase_price))
                                .attr('data-price', data.purchase_price);
                            row.find('.unit').val(data.unit);
                            row.find('.stock').val(data.stock);
                            calculateTotal(row);
                        });
                    }
                }

                function formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(angka);
                }


                $(document).on('input', '.qty', function() {
                    let row = $(this).closest('tr');
                    calculateTotal(row);
                    calculateTotalQty();
                });

                $(document).on('input', '.discount1, .discount2, .discount3', function() {
                    calculateSubTotal();
                });

                function calculateTotal(row) {
                    let price = parseFloat(row.find('.price').attr('data-price')) || 0;
                    let qty = parseFloat(row.find('.qty').val()) || 0;
                    let discount1 = parseFloat(row.find('.discount1').val()) || 0;
                    let discount2 = parseFloat(row.find('.discount2').val()) || 0;
                    let discount3 = parseFloat(row.find('.discount3').val()) || 0;

                    let originalTotal = price * qty;
                    let discountAmount1 = (originalTotal * discount1) / 100;
                    let discountAmount2 = (originalTotal * discount2) / 100;
                    let discountAmount3 = (originalTotal * discount3) / 100;

                    let total = originalTotal - discountAmount1 - discountAmount2 - discountAmount3;

                    row.find('.total-price')
                        .val(formatRupiah(total))
                        .attr('data-total', total.toFixed(2));
                    calculateSubTotal();
                }


                function calculateSubTotal() {
                    let subTotal = 0;
                    let totalDiscount1 = 0;
                    let totalDiscount2 = 0;
                    let totalDiscount3 = 0;

                    $('.price').each(function() {
                        let row = $(this).closest('tr');
                        let price = parseFloat($(this).attr('data-price')) || 0;
                        let qty = parseFloat(row.find('.qty').val()) || 0;
                        let discount1 = parseFloat(row.find('.discount1').val()) || 0;
                        let discount2 = parseFloat(row.find('.discount2').val()) || 0;
                        let discount3 = parseFloat(row.find('.discount3').val()) || 0;

                        let originalTotal = price * qty;
                        let discountAmount1 = (originalTotal * discount1) / 100;
                        let discountAmount2 = (originalTotal * discount2) / 100;
                        let discountAmount3 = (originalTotal * discount3) / 100;

                        totalDiscount1 += discountAmount1;
                        totalDiscount2 += discountAmount2;
                        totalDiscount3 += discountAmount3;
                        subTotal += originalTotal;
                    });

                    let totalDiscount = totalDiscount1 + totalDiscount2 + totalDiscount3;
                    let totalPrice = subTotal - totalDiscount;

                    $('#sub_total').val(formatRupiah(subTotal));
                    $('#total_discount1').val(formatRupiah(totalDiscount1));
                    $('#total_discount2').val(formatRupiah(totalDiscount2));
                    $('#total_discount3').val(formatRupiah(totalDiscount3));
                    $('#total_price').val(formatRupiah(totalPrice));
                    calculateTotalPrice(totalPrice);
                }

                function calculateTotalPrice(totalPrice) {
                    let taxType = $('#tax').val();
                    let taxRate = (taxType === 'ppn') ? 0.11 : 0;
                    let taxAmount = totalPrice * taxRate;
                    let finalTotalPrice = totalPrice + taxAmount;

                    $('#taxRate').val(formatRupiah(taxAmount));
                    $('#total_price').val(formatRupiah(finalTotalPrice));
                }

                $('#tax').on('change', function() {
                    calculateTotalPrice(parseFloat($('#total_price').attr('data-total')) || 0);
                });

                function calculateTotalQty() {
                    let totalQty = 0;
                    $('.qty').each(function() {
                        totalQty += parseFloat($(this).val()) || 0;
                    });
                    $('#total_qty').val(totalQty);
                }

                $('#add-item').click(function() {
                    let supplierId = $('#supplier_id').val();
                    let taxVal = $('#tax').val();

                    $('#supplier_null').text(supplierId ? '' : 'Silakan pilih supplier terlebih dahulu!');
                    $('#tax_null').text(taxVal ? '' : 'Silakan pilih pajak terlebih dahulu!');

                    if (!supplierId || !taxVal) return;

                    let row = `
                        <tr class="border-b border-gray-300">
                                <td class="px-1 py-1">
                                    <input type="text" class="item-code w-32 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly>
                                </td>
                                <td class="px-1 py-1">
                                    <select name="items[]" class="item-select w-64 select2 px-1 py-1 border border-gray-300 rounded-md">
                                    <option value="">Pilih Barang</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                    </select>
                                </td>
                                <td class="px-1 py-1">
                                    <input type="number" name="stock[]" class="stock w-20 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly>
                                </td>
                                <td class="px-1 py-1">
                                    <input type="text" name="unit[]" class="unit w-20 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly>
                                </td>
                                <td class="px-1 py-1">
                                    <input type="number" name="qty[]" class="qty w-20 px-2 py-1 border border-gray-300 rounded-md text-center" min="1" value="1" required data-parsley-required-message="Jumlah harus diisi">
                                </td>
                                <td class="px-1 py-1">
                                    <input type="text" name="price_per_item[]" class="price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly>
                                </td>
                                <td class="px-1 py-1">
                                    <div class="flex space-x-1">
                                        <input type="text" name="discount1[]" class="discount1 w-8 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="D1">
                                        <input type="text" name="discount2[]" class="discount2 w-8 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="D2">
                                        <input type="text" name="discount3[]" class="discount3 w-8 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="D3">
                                        <input type="text" name="ad[]" class="ad w-8 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="AD">
                                    </div>
                                </td>
                                <td class="px-1 py-1">
                                    <input type="text" name="prices[]" class="total-price w-40 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <button type="button" class="remove-item px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        `;

                    $('#items-table tbody').append(row);
                    $('.item-select').select2();
                });

                $(document).on('click', '.remove-item', function() {
                    $(this).closest('tr').remove();
                    calculateSubTotal();
                    calculateTotalQty();
                });

                $('#purchase-form').parsley();

                $('#supplier_id').select2();

                $('#buttonSubmit').on('click', function(event) {
                    let isValid = true;

                    $('#supplier_id').each(function() {
                        if ($(this).val() === null || $(this).val() === "") {
                            $(this).next('.select2-container').find('.select2-selection').addClass(
                                'error');
                            isValid = false;
                        } else {
                            $(this).next('.select2-container').find('.select2-selection').removeClass(
                                'error');
                        }
                    });
                });

                function calculateTotalQty() {
                    let totalQty = 0;
                    $('.qty').each(function() {
                        totalQty += parseFloat($(this).val()) || 0;
                    });
                    $('#total_qty').val(totalQty);
                }
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
