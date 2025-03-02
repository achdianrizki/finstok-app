<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">{{ __('Tambah Penjualan') }}</h2>
    </x-slot>

    <form id="sales-form" action="{{ route('manager.sales.store') }}" method="POST" data-parsley-validate>
        @csrf
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    {{-- <x-form.label for="sale_number" :value="__('Nomor Penjualan')" />
                    <x-form.input id="sale_number" class="block w-full" type="text" name="sale_number" /> --}}

                    <x-form.label for="sale_date" :value="__('Tanggal Penjualan')" />
                    <x-form.input id="sale_date" class="block w-full flatpickr-input" type="date" name="sale_date"
                        autocomplete="off" required data-parsley-required-message="Tanggal Penjualan wajib diisi" />

                    <x-form.label for="buyer_id" :value="__('Pelanggan')" />
                    <x-form.select id="buyer_id" name="buyer_id" class="w-full select2" required
                        data-parsley-required-message="Pilih salah satu pelanggan">
                        <option value="" selected disabled>{{ __('Pilih Pelanggan') }}</option>
                        @foreach ($buyers as $buyer)
                            <option value="{{ $buyer->id }}">{{ $buyer->contact }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.label for="salesman_id" :value="__('Sales')" />
                    <x-form.select id="salesman_id" name="salesman_id" class="w-full select2">
                        <option value="" selected disabled>{{ __('Pilih Sales') }}</option>
                        @foreach ($salesmans as $salesman)
                            <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.label for="tax" :value="__('Pajak')" />
                    <x-form.select id="tax" class="block w-full" name="tax" required
                        data-parsley-required-message="Pajak wajib diisi">
                        <option value="" disabled selected>Pilih</option>
                        <option value="ppn" {{ old('tax') == 'ppn' ? 'selected' : '' }}>PPN 11%</option>
                        <option value="non_ppn" {{ old('tax') == 'non_ppn' ? 'selected' : '' }}>NON-PPN</option>
                    </x-form.select>

                </div>

                <div>
                    <x-form.label for="information" :value="__('Keterangan')" class="mb-2" />
                    <textarea id="information" name="information"
                        class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                        rows="3" placeholder="Deskripsi penjualan">{{ old('information') }}</textarea>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <h3 class="text-lg font-semibold">{{ __('Barang') }}</h3>
            <hr class="my-2 border-gray-300">
        </div>

        <div class="mt-5 space-y-2">

            <button type="button" id="add-item" class="mt-2 px-4 py-2 bg-purple-500 text-white rounded">+ Tambah
                Barang
            </button>

            <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-lg shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max border border-gray-300 shadow-md table-auto" id="items-table">
                        <thead class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Kode Barang</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Nama Barang</th>
                                <th class="px-2 py-2 text-center border-b border-gray-300 ">Stok</th>
                                <th class="px-2 py-2 text-center border-b border-gray-300 ">Satuan</th>
                                <th class="px-3 py-2 text-center border-b border-gray-300 ">Jumlah</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Harga Beli per pcs</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Harga Jual per pcs</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Diskon (%)</th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Total Harga Setelah Diskon
                                </th>
                                <th class="px-4 py-2 text-center border-b border-gray-300 ">Total Harga Sebelum Diskon
                                </th>
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

            <div class="grid justify-items-end mt-4 space-y-2">
                <div class="flex justify-between items-center w-full max-w-md">
                    <label for="status" class="mr-4">Status Pembayaran</label>
                    <select name="status" id="status" class="w-1/2 border-gray-300 rounded-md p-2">
                        <option value="belum_lunas" selected>Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                    </select>
                </div>
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
                $("#sale_date").flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
                });

                $('#buyer_id').select2();

                $('#buyer_id').on('change', function() {
                    let supplierId = $(this).val();
                    $('.item-select').each(function() {
                        let row = $(this).closest('tr');
                        let itemId = $(this).val();
                    });
                });

                $(document).on('change', '.item-select', function() {
                    let row = $(this).closest('tr');
                    let itemId = $(this).val();
                    let supplierId = $('#salesman_id').val();
                });

                $('#salesman_id').select2();

                $('#salesman_id').on('change', function() {
                    let supplierId = $(this).val();
                    $('.item-select').each(function() {
                        let row = $(this).closest('tr');
                        let itemId = $(this).val();
                    });
                });

                $(document).on('change', '.item-select', function() {
                    let row = $(this).closest('tr');
                    let itemId = $(this).val();
                    let supplierId = $('#salesman_id').val();
                    updateItemData(row, itemId);
                });

                function updateItemData(row, itemId) {
                    if (itemId) {
                        $.get(`/get-sales-item/${itemId}`, function(data) {
                            row.find('.item-code').val(data.code);
                            row.find('.item-name').val(data.name);
                            row.find('.price').val(data.purchase_price);
                            row.find('.unit').val(data.unit);
                            row.find('.stock').val(data.stock);
                            row.find('.qty').val();
                            calculateTotal(row);
                        });
                    }
                }

                $(document).on('input', '.sale_price, .qty, .discount1, .discount2, .discount3', function() {
                    let row = $(this).closest('tr');
                    calculateTotal(row);
                });

                $(document).on('change', '#tax', function() {
                    calculateTotalPrice();
                });

                function calculateTotal(row) {
                    let salePrice = parseFloat(row.find('.sale_price').val()) || 0;
                    let qty = parseFloat(row.find('.qty').val()) || 0;
                    let discount1 = parseFloat(row.find('.discount1').val()) || 0;
                    let discount2 = parseFloat(row.find('.discount2').val()) || 0;
                    let discount3 = parseFloat(row.find('.discount3').val()) || 0;

                    let originalTotal = salePrice * qty;

                    let discountAmount1 = (originalTotal * discount1 / 100);
                    let discountAmount2 = (originalTotal * discount2 / 100);
                    let discountAmount3 = (originalTotal * discount3 / 100);

                    let total = originalTotal - discountAmount1 - discountAmount2 - discountAmount3;

                    row.find('.total-price').val(total.toFixed(2));

                    calculateSubTotal();
                }

                function calculateSubTotal() {
                    let subTotal = 0;
                    let totalDiscount1 = 0;
                    let totalDiscount2 = 0;
                    let totalDiscount3 = 0;

                    $('.price').each(function() {
                        let row = $(this).closest('tr');
                        let salePrice = parseFloat(row.find('.sale_price').val()) || 0;
                        let qty = parseFloat(row.find('.qty').val()) || 0;
                        let discount1 = parseFloat(row.find('.discount1').val()) || 0;
                        let discount2 = parseFloat(row.find('.discount2').val()) || 0;
                        let discount3 = parseFloat(row.find('.discount3').val()) || 0;

                        let originalTotal = salePrice * qty;

                        let discountAmount1 = (originalTotal * discount1 / 100);
                        let discountAmount2 = (originalTotal * discount2 / 100);
                        let discountAmount3 = (originalTotal * discount3 / 100);

                        let finalTotal = originalTotal - discountAmount1 - discountAmount2 - discountAmount3;

                        // totalDiscount1 += originalTotal - afterDiscount1;
                        // totalDiscount2 += afterDiscount1 - afterDiscount2;
                        // totalDiscount3 += afterDiscount2 - afterDiscount3;
                        totalDiscount1 += discountAmount1;
                        totalDiscount2 += discountAmount2;
                        totalDiscount3 += discountAmount3;

                        subTotal += originalTotal;
                        row.find('.real_price').val(originalTotal.toFixed(2));

                    });

                    let totalDiscount = totalDiscount1 + totalDiscount2 + totalDiscount3;
                    let totalPrice = subTotal - totalDiscount;

                    $('#sub_total').val(subTotal.toFixed(2));
                    $('#total_discount1').val(totalDiscount1.toFixed(2));
                    $('#total_discount2').val(totalDiscount2.toFixed(2));
                    $('#total_discount3').val(totalDiscount3.toFixed(2));
                    $('#total_price').val(totalPrice.toFixed(2));

                    calculateTotalPrice(totalPrice);
                }

                function calculateTotalPrice(totalPrice = parseFloat($('#total_price').val()) || 0) {
                    let taxType = $('#tax').val();
                    let taxRate = (taxType === 'ppn') ? 0.11 : 0;

                    let taxAmount = totalPrice * taxRate;
                    let finalTotalPrice = totalPrice + taxAmount;

                    console.log("Tax Rate : " + taxRate);
                    console.log("Total Pajak : " + taxAmount.toFixed(2));
                    console.log("Total Harga Setelah Pajak : " + finalTotalPrice.toFixed(2));

                    $('#taxRate').val(taxAmount.toFixed(2));
                    $('#total_price').val(finalTotalPrice.toFixed(2));
                }

                $('#add-item').click(function() {
                    let supplierId = $('#salesman_id').val();
                    $('#supplier_null').text(!supplierId ?
                        'Anda belum memilih sales, tetapi Anda tetap bisa menambahkan barang.' : '');
                    // if (!supplierId) return;

                    let row = `
                <tr class="border-b border-gray-300">
                    <td class="px-1 py-2">
                    <input type="text" class="item-code w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly></td>
                    <td class="px-1 py-2">
                    <select name="items[]" class="item-select w-40 select2 px-2 py-1 border border-gray-300 rounded-md" required data-parsley-required-message="Barang harus diisi">
                            <option value="">Pilih Barang</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                    </select>
                    </td> 
                    <td class="px-1 py-2"><input type="number" name="stock[]" class="stock w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly></td>
                    <td class="px-1 py-2"><input type="text" name="unit[]" class="unit w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly></td>
                    <td class="px-1 py-2"><input type="number" name="qty_sold[]" class="qty w-16 px-2 py-1 border border-gray-300 rounded-md text-center" min="1" value="1" required data-parsley-required-message="Jumlah harus diisi"></td>
                    <td class="px-1 py-2"><input type="text" name="prices[]" class="price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly></td>
                    <td class="px-1 py-2"><input type="number" name="sale_prices[]" class="sale_price w-full px-2 py-1 border border-gray-300 rounded-md text-right" required data-parsley-required-message="Harga jual harus diisi"></td>
                    <td class="px-1 py-2">
                    <div class="flex space-x-1">
                    <input type="text" name="discount1[]" class="discount1 w-8 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="D1">
                    <input type="text" name="discount2[]" class="discount2 w-8 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="D2">
                    <input type="text" name="discount3[]" class="discount3 w-8 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="D3">
                    <input type="text" name="ad[]" class="ad w-8 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="AD">
                    </div>
                    </td>
                    <td class="px-1 py-2"><input type="text" name="total_prices[]" class="total-price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly></td>
                    <td class="px-1 py-2"><input type="text" name="real_prices[]" class="real_price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly></td>
                    <td class="px-1 py-2 text-center"><button type="button" class="remove-item px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">Hapus</button></td>
                </tr>
                `;

                    $('#items-table tbody').append(row);
                    $('.item-select').select2();
                });

                $(document).on('click', '.remove-item', function() {
                    $(this).closest('tr').remove();
                    calculateSubTotal();
                });

                $('sales-form').parsley();

                $('#buyer_id').select2(); // Inisialisasi Select2

                $('#buttonSubmit').on('click', function(event) {
                    let isValid = true;

                    $('#buyer_id').each(function() {
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

                // $('#buyer_id').on('change', function() {
                //     if ($(this).val() !== null && $(this).val() !== "") {
                //         $(this).siblings('.select2-container').find('.select2-selection').addClass('success');
                //     }
                // });

                $('#buyer_id').on('change', function() {
                    if ($(this).val() !== null && $(this).val() !== "") {
                        $(this).siblings('.select2-container').find('.select2-selection').removeClass(
                            'error');
                    }
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
