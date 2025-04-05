<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">{{ __('Edit Data Penjualan') }}</h2>
    </x-slot>

    <form id="edit-form" action="{{ route('manager.sales.update', $sale->id) }}" method="POST" data-parsley-validate>
        @csrf
        @method('PUT')
        <input type="hidden" id="sale_id" value="{{ $sale->id }}">
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-form.label for="sale_number" :value="__('Nomor Penjualan')" />
                    <x-form.input id="sale_number" class="block w-full" type="text" name="sale_number"
                        :value="old('name', $sale->sale_number)" readonly />

                    <x-form.label for="sale_date" :value="__('Tanggal Penjualan')" />
                    <x-form.input id="sale_date" class="block w-full flatpickr-input" type="date" name="sale_date"
                        :value="old('name', $sale->sale_date)" readonly />

                    <x-form.label for="buyer_id" :value="__('Pembeli')" />
                    <select id="buyer_id" name="buyer_id" class="w-full select2"
                        data-parsley-required-message="Pilih salah satu buyer">
                        <option value="" selected disabled>Pilih</option>
                        @foreach ($buyers as $buyer)
                            <option value="{{ $buyer->id }}"
                                {{ old('buyer_id', $sale->buyer_id) == $buyer->id ? 'selected' : '' }}>
                                {{ $buyer->name }}</option>
                        @endforeach
                    </select>

                    <x-form.label for="salesman_id" :value="__('Sales')" />
                    <x-form.select id="salesman_id" name="salesman_id" class="w-full select2">
                        <option value="" selected disabled>{{ __('Pilih Sales') }}</option>
                        @foreach ($salesmans as $salesman)
                            <option value="{{ $salesman->id }}"
                                {{ old('salesman_id', $sale->salesman_id) == $salesman->id ? 'selected' : '' }}>
                                {{ $salesman->name }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.label for="tax" :value="__('Pajak')" />
                    <x-form.select id="tax" class="block w-full" name="tax_type">
                        <option value="" disabled selected>Pilih</option>
                        <option value="ppn" {{ old('tax_type', $sale->tax_type) == 'ppn' ? 'selected' : '' }}>PPN
                            11%</option>
                        <option value="non_ppn" {{ old('tax_type', $sale->tax_type) == 'non_ppn' ? 'selected' : '' }}>
                            NON-PPN</option>
                    </x-form.select>

                    <div>
                        <x-form.label for="due_date_duration" :value="__('Durasi Jatuh Tempo (hari)')" class="mb-2" />
                        <div class="flex gap-2 mb-2">
                            <button type="button"
                                class="px-4 py-2 {{ $sale->due_date_duration == 14 ? 'bg-purple-700 ring-2' : 'bg-purple-500' }} text-white rounded-md duration-btn"
                                data-value="14">14</button>
                            <button type="button"
                                class="px-4 py-2 {{ $sale->due_date_duration == 30 ? 'bg-purple-700 ring-2' : 'bg-purple-500' }} text-white rounded-md duration-btn"
                                data-value="30">30</button>
                            <button type="button"
                                class="px-4 py-2 {{ $sale->due_date_duration == 45 ? 'bg-purple-700 ring-2' : 'bg-purple-500' }} text-white rounded-md duration-btn"
                                data-value="45">45</button>
                            <input type="number" id="custom_due_date"
                                class="w-20 border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                                placeholder="Custom"
                                value="{{ old('due_date_duration', $sale->due_date_duration != 14 && $sale->due_date_duration != 30 && $sale->due_date_duration != 45 ? $sale->due_date_duration : '') }}">
                        </div>
                        <p id="due_date_error" class="text-red-500 mt-2 hidden">Durasi jatuh tempo harus diisi</p>
                        <p id="sale_date_error" class="text-red-500 mt-2 hidden">Pilih tanggal terlebih dahulu!</p>
                        <input type="hidden" id="due_date_duration" name="due_date_duration"
                            value="{{ old('due_date_duration', $sale->due_date_duration) }}">
                        <input type="hidden" id="due_date" name="due_date"
                            value="{{ old('due_date', $sale->due_date) }}">
                    </div>
                </div>

                <div>
                    <x-form.label for="information" :value="__('Keterangan')" class="mb-2" />
                    <textarea id="information" name="information"
                        class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                        rows="3" placeholder="Keterangan">{{ old('information', $sale->information) }}</textarea>

                    <x-form.label for="warehouse_id" :value="__('Gudang')" class="mb-2" />
                    <x-form.select id="warehouse_id" class="block w-full pointer-events-none bg-gray-200"
                        name="warehouse_id">
                        @foreach ($warehouses as $warehouse)
                            @php
                                $selectedWarehouse = $sale->items
                                    ->pluck('pivot.warehouse_id')
                                    ->contains($warehouse->id);
                            @endphp
                            <option value="{{ $warehouse->id }}"
                                {{ old('warehouse_id', $selectedWarehouse ? $warehouse->id : null) == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </x-form.select>
                    <input type="hidden" name="" id="warehouse_id_selected"
                        value="{{ $sale->items->first()->pivot->warehouse_id ?? '' }}">
                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold">{{ __('Barang') }}</h3>
            <hr class="my-2 border-gray-300">
        </div>

        <div class="mt-5 space-y-2">
            <p id="buyer_null" class="text-red-500 mt-2"></p>

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
                            @foreach ($sale->items as $index => $item)
                                <tr class="border-b border-gray-300">
                                    <td class="px-1 py-2">
                                        <input type="text"
                                            class="item-code w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->code }}">
                                    </td>
                                    <td class="px-1 py-2">
                                        <select name="items[]"
                                            class="item-select w-full select2 px-2 py-1 border border-gray-300 rounded-md pointer-events-none bg-gray-200"
                                            data-locked="true">
                                            <option value="">Pilih Barang</option>
                                            @foreach ($items as $availableItem)
                                                <option value="{{ $availableItem->id }}"
                                                    {{ $item->id == $availableItem->id ? 'selected' : '' }}>
                                                    {{ $availableItem->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-1 py-2"><input type="number" name="stock[]"
                                            class="stock w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->stock }}"></td>
                                    <td class="px-1 py-2"><input type="text" name="unit[]"
                                            class="unit w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->unit }}"></td>
                                    <td class="px-1 py-2">
                                        <input type="number" name="qty_sold[]"
                                            class="qty_sold w-16 px-2 py-1 border border-gray-300 rounded-md text-center"
                                            value="{{ $item->pivot->qty_sold }}">
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="text" name="sale_prices[]"
                                            class="sale_price w-full px-2 py-1 border border-gray-300 rounded-md text-right"
                                            required data-parsley-required-message="Harga jual harus diisi"
                                            value="{{ number_format($item->pivot->sale_price, 2, ',', '.') }}">
                                    </td>
                                    <td class="px-1 py-2">
                                        <div class="flex space-x-1">
                                            <input type="text" name="discount1[]"
                                                class="discount1 w-10 px-1 py-1 border border-gray-300 rounded-md text-right"
                                                placeholder="D1" value="{{ $item->pivot->discount1 }}">
                                            <input type="text" name="discount2[]"
                                                class="discount2 w-10 px-1 py-1 border border-gray-300 rounded-md text-right"
                                                placeholder="D2" value="{{ $item->pivot->discount2 }}">
                                            <input type="text" name="discount3[]"
                                                class="discount3 w-10 px-1 py-1 border border-gray-300 rounded-md text-right"
                                                placeholder="D3" value="{{ $item->pivot->discount3 }}">
                                            <input type="text" name="ad[]"
                                                class="ad w-8 px-1 py-1 border border-gray-300 rounded-md text-right"
                                                placeholder="AD" value="{{ $item->pivot->ad }}">
                                        </div>
                                    </td>
                                    <td class="px-1 py-2"><input type="text" name="total_prices[]"
                                            class="total-price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                            readonly
                                            value="{{ number_format($item->pivot->sale_price * $item->pivot->qty_sold - $sale->total_discount, 2, ',', '.') }}">
                                    </td>
                                    <td class="px-1 py-2"><input type="text" name="real_prices[]"
                                            class="real_price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                            readonly
                                            value="{{ number_format($item->pivot->sale_price * $item->pivot->qty_sold, 2, ',', '.') }}">
                                    </td>
                                    <td class="px-1 py-2 text-center"><button type="button"
                                            class="remove-item px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid justify-items-end mt-4 space-y-2">
            <div class="flex justify-between items-center w-full max-w-md">
                <label for="sub_total" class="mr-4">Sub Total</label>
                <input type="text" class="w-1/2 border-gray-500 rounded-md p-2 bg-gray-100" name="sub_total"
                    id="sub_total" readonly value="{{ number_format($sale->sub_total, 2, ',', '.') }}">
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="discount1_value" class="mr-4">Diskon 1</label>
                <input type="text" class="w-1/2 border-gray-500 rounded-md p-2 bg-gray-100" name="discount1_value"
                    id="discount1_value" readonly value="{{ number_format($sale->discount1_value, 2, ',', '.') }}">
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="discount2_value" class="mr-4">Diskon 2</label>
                <input type="text" class="w-1/2 border-gray-500 rounded-md p-2 bg-gray-100" name="discount2_value"
                    id="discount2_value" readonly value="{{ number_format($sale->discount2_value, 2, ',', '.') }}">
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="discount3_value" class="mr-4">Diskon 3</label>
                <input type="text" class="w-1/2 border-gray-500 rounded-md p-2 bg-gray-100" name="discount3_value"
                    id="discount3_value" readonly value="{{ number_format($sale->discount3_value, 2, ',', '.') }}">
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="tax" class="mr-4">PPN 11%</label>
                <input type="text" class="w-1/2 border-gray-500 rounded-md p-2 bg-gray-100" name="tax"
                    id="taxRate" readonly value="{{ number_format($sale->tax, 2, ',', '.') }}">
            </div>

            <div class="flex justify-between items-center w-full max-w-md">
                <label for="total_price" class="mr-4">Total Price</label>
                <input type="text" class="w-1/2 border-gray-500 rounded-md p-2 bg-gray-100" name="total_price"
                    id="total_price" readonly value="{{ number_format($sale->total_price, 2, ',', '.') }}">
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

        {{-- <input type="submit" value="Submit"> --}}
    </form>

    @push('scripts')
        <script>
            $(document).ready(function() {

                // $(".duration-btn").prop("disabled", true).addClass("bg-gray-400");
                // $("#custom_due_date").prop("disabled", true).addClass("bg-gray-400");

                $(".duration-btn").on("click", function() {
                    $(".duration-btn").removeClass("bg-purple-700 ring-2 ring-purple-300");
                    $(".duration-btn").addClass("bg-purple-500");

                    $(this).addClass("bg-purple-700 ring-2 ring-purple-300");

                    let days = parseInt($(this).data("value"));

                    let saleDateValue = $("#sale_date").val();

                    let dueDate = new Date(saleDateValue);
                    dueDate.setDate(dueDate.getDate() + days);

                    let year = dueDate.getFullYear();
                    let month = String(dueDate.getMonth() + 1).padStart(2, '0'); // Bulan mulai dari 0
                    let day = String(dueDate.getDate()).padStart(2, '0');

                    let formattedDueDate = `${year}-${month}-${day}`;

                    $("#due_date_duration").val(days);
                    $("#due_date").val(formattedDueDate);

                    $("#custom_due_date").val("");

                    $("#due_date_error").addClass("hidden");
                    $("#sale_date_error").addClass("hidden");
                });

                $("#custom_due_date").on("input", function() {

                    $(".duration-btn").removeClass("bg-purple-700 ring-2 ring-purple-300");

                    let days = parseInt($(this).val());

                    if (!isNaN(days)) {

                        let saleDateValue = $("#sale_date").val();

                        let dueDate = new Date(saleDateValue);
                        dueDate.setDate(dueDate.getDate() + days);

                        let year = dueDate.getFullYear();
                        let month = String(dueDate.getMonth() + 1).padStart(2, '0'); // Bulan mulai dari 0
                        let day = String(dueDate.getDate()).padStart(2, '0');

                        let formattedDueDate = `${year}-${month}-${day}`;

                        $("#due_date_duration").val(days);
                        $("#due_date").val(formattedDueDate);

                        $("#due_date_error").addClass("hidden");
                        $("#sale_date_error").addClass("hidden");
                    }
                });

                $("#sale_date").flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    onChange: function(selectedDates, dateStr) {
                        if (dateStr) {
                            $(".duration-btn").prop("disabled", false).removeClass("bg-gray-400");
                            $("#custom_due_date").prop("disabled", false).removeClass("bg-gray-400");
                            $("#due_date_error").addClass("hidden");
                            $("#sale_date_error").addClass("hidden");
                        }
                    }
                });

                $("#buttonSubmit").click(function(e) {
                    let dueDateDuration = $("#due_date_duration").val();
                    let saleDateValue = $("#sale_date").val();

                    if (!saleDateValue) {
                        e.preventDefault();
                        $("#sale_date_error").removeClass("hidden");
                    } else if (!dueDateDuration) {
                        e.preventDefault();
                        $("#due_date_error").removeClass("hidden");
                    } else {
                        $("#sale_date_error").addClass("hidden");
                        $("#due_date_error").addClass("hidden");
                    }
                });


                $('#buttonSubmit').click(function(event) {
                    const items = document.querySelectorAll('.item-select');

                    $('#error-message').remove();

                    if (items.length === 0) {
                        event.preventDefault();

                        $('#add-item').after(
                            '<p id="error-message" class="text-red-500 mt-2">Barang tidak boleh kosong</p>');
                    }
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

                $('.sale_price').on('input', function(e) {
                    let value = e.target.value.replace(/[^,\d]/g, '').toString();

                    let split = value.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/g);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;

                    $(this).val(rupiah);
                })

                $(document).on('change', '.item-select', function() {
                    let row = $(this).closest('tr');
                    let itemId = $(this).val();
                    let supplierId = $('#salesman_id').val();
                    updateItemData(row, itemId);

                    let selectedItem = $(this).val();
                    let stock = $(this).find(':selected').data('stock');
                    row.find('.stock').val(stock);
                    disableSelectedItems();

                    $('.sale_price').on('input', function(e) {
                        let value = e.target.value.replace(/[^,\d]/g, '').toString();

                        let split = value.split(',');
                        let sisa = split[0].length % 3;
                        let rupiah = split[0].substr(0, sisa);
                        let ribuan = split[0].substr(sisa).match(/\d{3}/g);

                        if (ribuan) {
                            let separator = sisa ? '.' : '';
                            rupiah += separator + ribuan.join('.');
                        }

                        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;

                        $(this).val(rupiah);
                    })
                });

                function formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }).format(number);
                }

                function updateItemData(row, itemId) {
                    if (itemId) {
                        $.get(`/get-sales-item/${itemId}`, function(data) {
                            row.find('.item-code').val(data.code);
                            row.find('.item-name').val(data.name);
                            row.find('.sale_price').val(formatRupiah(data.purchase_price));
                            row.find('.unit').val(data.unit);
                            row.find('.stock').val(data.stock);
                            row.find('.qty_sold').val();
                            calculateTotal(row);
                        });
                    }
                }

                $(document).on('input', '.sale_price, .qty_sold, .discount1, .discount2, .discount3', function() {
                    let row = $(this).closest('tr');
                    calculateTotal(row);

                });

                $(document).on('change', '#tax', function() {
                    calculateSubTotal();
                });

                function calculateTotal(row) {
                    let salePrice = parseFloat(row.find('.sale_price').val().replace(/\./g, '').replace(',', '.')) || 0;
                    let qty = parseFloat(row.find('.qty_sold').val()) || 0;
                    let discount1 = parseFloat(row.find('.discount1').val()) || 0;
                    let discount2 = parseFloat(row.find('.discount2').val()) || 0;
                    let discount3 = parseFloat(row.find('.discount3').val()) || 0;

                    console.log("Harga Jual:", salePrice); // Debugging
                    console.log("Qty:", qty);

                    let originalTotal = salePrice * qty;
                    let discountAmount1 = (originalTotal * discount1 / 100);
                    let discountAmount2 = (originalTotal * discount2 / 100);
                    let discountAmount3 = (originalTotal * discount3 / 100);

                    let total = originalTotal - discountAmount1 - discountAmount2 - discountAmount3;

                    row.find('.total-price').val(formatRupiah(total.toFixed(2)));

                    calculateSubTotal();
                }

                function calculateSubTotal() {
                    let subTotal = 0;
                    let totalDiscount1 = 0;
                    let totalDiscount2 = 0;
                    let totalDiscount3 = 0;

                    $('.sale_price').each(function() {
                        let row = $(this).closest('tr');
                        let salePrice = parseFloat(row.find('.sale_price').val().replace(/\./g, '').replace(',',
                            '.')) || 0;
                        let qty = parseFloat(row.find('.qty_sold').val()) || 0;
                        let discount1 = parseFloat(formatRupiah(row.find('.discount1').val())) || 0;
                        let discount2 = parseFloat(row.find('.discount2').val()) || 0;
                        let discount3 = parseFloat(row.find('.discount3').val()) || 0;

                        let originalTotal = salePrice * qty;

                        let discountAmount1 = (originalTotal * discount1 / 100);
                        let discountAmount2 = (originalTotal * discount2 / 100);
                        let discountAmount3 = (originalTotal * discount3 / 100);

                        let finalTotal = originalTotal - discountAmount1 - discountAmount2 - discountAmount3;

                        totalDiscount1 += discountAmount1;
                        totalDiscount2 += discountAmount2;
                        totalDiscount3 += discountAmount3;

                        subTotal += originalTotal;
                        row.find('.real_price').val(formatRupiah(originalTotal.toFixed(2)));

                    });

                    let totalDiscount = totalDiscount1 + totalDiscount2 + totalDiscount3;
                    let totalPrice = subTotal - totalDiscount;

                    $('#sub_total').val(formatRupiah(subTotal.toFixed(2)));
                    $('#discount1_value').val(formatRupiah(totalDiscount1.toFixed(2)));
                    $('#discount2_value').val(formatRupiah(totalDiscount2.toFixed(2)));
                    $('#discount3_value').val(formatRupiah(totalDiscount3.toFixed(2)));
                    $('#total_price').val(formatRupiah(totalPrice.toFixed(2)));

                    calculateTotalPrice();
                }

                function calculateTotalPrice() {
                    let totalPrice = parseFloat($('#total_price').val().replace(/\./g, '')
                        .replace(',', '.')) || 0;
                    let taxType = $('#tax').val();
                    let taxRate = (taxType === 'ppn') ? 0.11 : 0;

                    let taxAmount = totalPrice * taxRate;
                    let finalTotalPrice = totalPrice + taxAmount;

                    $('#taxRate').val(formatRupiah(taxAmount.toFixed(2)));
                    $('#total_price').val(formatRupiah(finalTotalPrice.toFixed(2)));
                }

                $(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    let itemOptions = '<option value="">Pilih Barang</option>';

                    $('#warehouse_id_selected', function() {
                        let warehouseId = $('#warehouse_id_selected').val();
                        console.log('Warehouse ID:', warehouseId);

                        // Reset tabel saat gudang diubah

                        $.ajax({
                            url: `/get-items/warehouse`,
                            type: 'POST',
                            data: {
                                warehouse_id: warehouseId
                            },
                            dataType: 'json',
                            cache: false,
                            success: function(data) {
                                console.log("Data dari server:", data);
                                itemOptions = '<option value="">Pilih Barang</option>';

                                $.each(data, function(index, item) {
                                    let disabled = item.stock <= 0 ? 'disabled' :
                                        '';
                                    let name = item.stock <= 0 ?
                                        `${item.name} (Habis)` : item.name;

                                    itemOptions +=
                                        `<option value="${item.id}" data-stock="${item.stock}" ${disabled}>${name}</option>`;
                                });

                                let result = $('#items-table').data('itemOptions',
                                    itemOptions);
                                console.log('Item Options:', result);


                            },
                            error: function(xhr) {
                                console.log('Error:', xhr.responseText);
                            }
                        });
                    });

                    $('#add-item').click(function() {
                        let warehouseId = $('#warehouse_id').val();
                        if (!warehouseId) {
                            alert('Silakan pilih gudang terlebih dahulu!');
                            return;
                        }

                        let row = `
                            <tr class="border-b border-gray-300">
                                <td class="px-1 py-2">
                                    <input type="text" class="item-code w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <select name="items[]" class="item-select w-full select2 px-2 py-1 border border-gray-300 rounded-md" required>
                                        ${$('#items-table').data('itemOptions')}
                                    </select>
                                </td> 
                                <td class="px-1 py-2">
                                    <input type="number" name="stock[]" class="stock w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text" name="unit[]" class="unit w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="number" name="qty_sold[]" class="qty_sold w-16 px-2 py-1 border border-gray-300 rounded-md text-center" min="1" value="1" required>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text" name="sale_prices[]" class="sale_price w-full px-2 py-1 border border-gray-300 rounded-md text-right" required>
                                </td>
                                <td class="px-1 py-2">
                                    <div class="flex space-x-1">
                                        <input type="text" name="discount1[]" class="discount1 w-10 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="D1">
                                        <input type="text" name="discount2[]" class="discount2 w-10 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="D2">
                                        <input type="text" name="discount3[]" class="discount3 w-10 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="D3">
                                        <input type="text" name="ad[]" class="ad w-8 px-1 py-1 border border-gray-300 rounded-md text-right" placeholder="AD">
                                    </div>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text" name="total_prices[]" class="total-price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text" name="real_prices[]" class="real_price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly>
                                </td>
                                <td class="px-1 py-2 text-center">
                                    <button type="button" class="remove-item px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">Hapus</button>
                                </td>
                            </tr>`;

                        $('#items-table tbody').append(row);

                        let $newSelect = $('.item-select').last();
                        $newSelect.select2();

                        $newSelect.find('option').each(function() {
                            let stock = $(this).attr('data-stock');
                            console.log(`ID: ${$(this).val()}, Stock: ${stock}`);

                            if (stock <= 0) {
                                $(this).prop('disabled', true);
                            }
                        });

                        $newSelect.trigger('change');
                    });


                });


                function disableSelectedItems() {
                    let selectedItems = $('.item-select').map(function() {
                        return $(this).val();
                    }).get();

                    $('.item-select').each(function() {
                        let select = $(this);
                        select.find('option').each(function() {
                            let optionValue = $(this).val();
                            let stock = $(this).data('stock');

                            if (selectedItems.includes(optionValue) && optionValue !== '' &&
                                optionValue !== select.val()) {
                                $(this).prop('disabled', true);
                            } else if (stock <= 0) {
                                $(this).prop('disabled', true);
                            } else {
                                $(this).prop('disabled', false);
                            }
                        });
                    });
                }

                $(document).on('change', '.item-select', function() {
                    disableSelectedItems();
                });

                $(document).on('click', '.remove-item', function() {
                    let row = $(this).closest('tr');
                    let itemId = row.find('.item-select').val();
                    let saleId = $('#sale_id').val();

                    if (!itemId) {
                        row.remove();
                        disableSelectedItems();
                        return;
                    }

                    Swal.fire({
                        title: "Hapus Barang?",
                        text: "Apakah Anda yakin ingin menghapus barang ini?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/sale-edit/${saleId}/item-delete/${itemId}`,
                                type: 'DELETE',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire("Terhapus!", "Barang berhasil dihapus.",
                                            "success");
                                        row.remove();
                                        calculateSubTotal();
                                        calculateTotalPrice();
                                        disableSelectedItems();
                                    } else {
                                        Swal.fire("Gagal!", "Barang tidak dapat dihapus.",
                                            "error");
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire("Error!",
                                        "Terjadi kesalahan saat menghapus barang.",
                                        "error");
                                    console.log(xhr);
                                }
                            });
                        }
                    });
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

            .duration-btn:disabled {
                background-color: #a0aec0;
                cursor: not-allowed;
                opacity: 0.6;
            }
        </style>
    @endpush
</x-app-layout>
