<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">{{ __('Edit Data Pembelian') }}</h2>
    </x-slot>

    <form id="edit-form" action="{{ route('manager.purchase.update', $purchase->id) }}" method="POST"
        data-parsley-validate>
        @csrf
        @method('PUT')
        <input type="hidden" id="purchase_id" value="{{ $purchase->id }}">
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-form.label for="purchase_date" :value="__('Tanggal Pembelian')" />
                    <x-form.input id="purchase_date" class="block w-full flatpickr-input" type="date"
                        name="purchase_date" autocomplete="off" required
                        data-parsley-required-message="Tanggal Penjualan wajib diisi"
                        value="{{ old('purchase_date', $purchase->purchase_date) }}" />

                    <x-form.label for="supplier_id" :value="__('Supplier')" />
                    <select id="supplier_id" name="supplier_id" class="w-full select2"
                        data-parsley-required-message="Pilih salah satu supplier">
                        <option value="" selected disabled>Pilih</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}</option>
                        @endforeach
                    </select>

                    <x-form.label for="tax" :value="__('Pajak')" />
                    <x-form.select id="tax" class="block w-full" name="tax_type">
                        <option value="" disabled selected>Pilih</option>
                        <option value="ppn" {{ old('tax_type', $purchase->tax_type) == 'ppn' ? 'selected' : '' }}>PPN
                            11%</option>
                        <option value="non_ppn"
                            {{ old('tax_type', $purchase->tax_type) == 'non_ppn' ? 'selected' : '' }}>NON-PPN</option>
                    </x-form.select>
                </div>

                <div class="space-y-2">
                    <x-form.label for="information" :value="__('Keterangan')" />
                    <textarea id="information" name="information"
                        class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                        rows="3" placeholder="Deskripsi barang">{{ old('information', $purchase->information) }}</textarea>

                    <x-form.label for="warehouse_id" :value="__('Gudang')" />
                    <x-form.select id="warehouse_id" class="block w-full pointer-events-none bg-gray-200" name="warehouse_id">
                        @foreach ($warehouses as $warehouse)
                            @php
                                $selectedWarehouse = $purchase->items
                                    ->pluck('pivot.warehouse_id')
                                    ->contains($warehouse->id);
                            @endphp
                            <option value="{{ $warehouse->id }}"
                                {{ old('warehouse_id', $selectedWarehouse ? $warehouse->id : null) == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </x-form.select>
                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                </div>

                <div>
                    <x-form.label for="due_date_duration" :value="__('Durasi Jatuh Tempo (hari)')" class="mb-2" />
                    <div class="flex gap-2 mb-2">
                        <button type="button"
                            class="px-4 py-2 {{ $purchase->due_date_duration == 14 ? 'bg-purple-700 ring-2' : 'bg-purple-500' }} text-white rounded-md duration-btn"
                            data-value="14">14</button>
                        <button type="button"
                            class="px-4 py-2 {{ $purchase->due_date_duration == 30 ? 'bg-purple-700 ring-2' : 'bg-purple-500' }} text-white rounded-md duration-btn"
                            data-value="30">30</button>
                        <button type="button"
                            class="px-4 py-2 {{ $purchase->due_date_duration == 45 ? 'bg-purple-700 ring-2' : 'bg-purple-500' }} text-white rounded-md duration-btn"
                            data-value="45">45</button>
                        <input type="number" id="custom_due_date"
                            class="w-20 border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                            placeholder="Custom"
                            value="{{ old('due_date_duration', $purchase->due_date_duration != 14 && $purchase->due_date_duration != 30 && $purchase->due_date_duration != 45 ? $purchase->due_date_duration : '') }}">
                    </div>
                    <p id="due_date_error" class="text-red-500 mt-2 hidden">Durasi jatuh tempo harus diisi</p>
                    <p id="purchase_date_error" class="text-red-500 mt-2 hidden">Pilih tanggal terlebih dahulu!</p>
                    <input type="hidden" id="due_date_duration" name="due_date_duration"
                        value="{{ old('due_date_duration', $purchase->due_date_duration) }}">
                    <input type="hidden" id="due_date" name="due_date"
                        value="{{ old('due_date', $purchase->due_date) }}">
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
                            @foreach ($purchase->items as $index => $item)
                                <tr class="border-b border-gray-300">
                                    <td class="px-1 py-2">
                                        <input type="text"
                                            class="item-code w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->code }}">
                                    </td>
                                    <td>
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
                                    <td class="px-1 py-2">
                                        <input type="number" name="stock[]"
                                            class="stock w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->pivot->stock }}">
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="text" name="unit[]"
                                            class="unit w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->unit }}">
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="number" name="qty[]"
                                            class="qty w-16 px-2 py-1 border border-gray-300 rounded-md text-center"
                                            value="{{ $item->pivot->qty }}">
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="text" name="price_per_item[]"
                                            class="price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                            readonly value="{{ $item->pivot->price_per_item }}">
                                    </td>
                                    <td class="px-1 py-2">
                                        <div class="flex space-x-1">
                                            <input type="text" name="discount1[]"
                                                class="discount1 w-10 px-1 py-1 border border-gray-300 rounded-md text-right"
                                                placeholder="D1"
                                                value="{{ ($item->pivot->discount1 ?? 0) > 0 ? $item->pivot->discount1 : 0 }}">
                                            <input type="text" name="discount2[]"
                                                class="discount2 w-10 px-1 py-1 border border-gray-300 rounded-md text-right"
                                                placeholder="D2"
                                                value="{{ ($item->pivot->discount2 ?? 0) > 0 ? $item->pivot->discount2 : 0 }}">
                                            <input type="text" name="discount3[]"
                                                class="discount3 w-10 px-1 py-1 border border-gray-300 rounded-md text-right"
                                                placeholder="D3"
                                                value="{{ ($item->pivot->discount3 ?? 0) > 0 ? $item->pivot->discount3 : 0 }}">
                                            <input type="text" name="ad[]"
                                                class="ad w-8 px-1 py-1 border border-gray-300 rounded-md text-right"
                                                placeholder="AD" value="{{ ($item->pivot->ad ?? 0) > 0 ? $item->pivot->ad : 0 }}">
                                        </div>
                                    </td>
                                    <td class="px-1 py-2">
                                        <input type="text" name="prices[]"
                                            class="total-price w-40 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                            readonly
                                            value="{{ number_format($item->pivot->price_per_item * $item->pivot->qty, 2, ',', '.') }}">
                                    </td>
                                    <td class="px-1 py-2 text-center">
                                        <button type="button"
                                            class="remove-item px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                            Hapus
                                        </button>
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
                    <option value="belum_lunas"
                        {{ old('status', $purchase->status ?? '') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas
                    </option>
                    <option value="lunas" {{ old('status', $purchase->status ?? '') == 'lunas' ? 'selected' : '' }}>
                        Lunas</option>
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
            $(document).ready(function() {

                // $(".duration-btn").prop("disabled", true).addClass("bg-gray-400");
                // $("#custom_due_date").prop("disabled", true).addClass("bg-gray-400");

                $(".duration-btn").on("click", function() {
                    $(".duration-btn").removeClass("bg-purple-700 ring-2 ring-purple-300");
                    $(".duration-btn").addClass("bg-purple-500");

                    $(this).addClass("bg-purple-700 ring-2 ring-purple-300");

                    let days = parseInt($(this).data("value"));

                    let purchaseDateValue = $("#purchase_date").val();

                    let dueDate = new Date(purchaseDateValue);
                    dueDate.setDate(dueDate.getDate() + days);

                    let year = dueDate.getFullYear();
                    let month = String(dueDate.getMonth() + 1).padStart(2, '0'); // Bulan mulai dari 0
                    let day = String(dueDate.getDate()).padStart(2, '0');

                    let formattedDueDate = `${year}-${month}-${day}`;

                    $("#due_date_duration").val(days);
                    $("#due_date").val(formattedDueDate);

                    $("#custom_due_date").val("");

                    $("#due_date_error").addClass("hidden");
                    $("#purchase_date_error").addClass("hidden");
                });

                $("#custom_due_date").on("input", function() {

                    $(".duration-btn").removeClass("bg-purple-700 ring-2 ring-purple-300");

                    let days = parseInt($(this).val());

                    if (!isNaN(days)) {

                        let purchaseDateValue = $("#purchase_date").val();

                        let dueDate = new Date(purchaseDateValue);
                        dueDate.setDate(dueDate.getDate() + days);

                        let year = dueDate.getFullYear();
                        let month = String(dueDate.getMonth() + 1).padStart(2, '0'); // Bulan mulai dari 0
                        let day = String(dueDate.getDate()).padStart(2, '0');

                        let formattedDueDate = `${year}-${month}-${day}`;

                        $("#due_date_duration").val(days);
                        $("#due_date").val(formattedDueDate);

                        $("#due_date_error").addClass("hidden");
                        $("#purchase_date_error").addClass("hidden");
                    }
                });

                $("#purchase_date").flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    onChange: function(selectedDates, dateStr) {
                        if (dateStr) {
                            $(".duration-btn").prop("disabled", false).removeClass("bg-gray-400");
                            $("#custom_due_date").prop("disabled", false).removeClass("bg-gray-400");
                            $("#due_date_error").addClass("hidden");
                            $("#purchase_date_error").addClass("hidden");
                        }
                    }
                });

                $("#buttonSubmit").click(function(e) {
                    let dueDateDuration = $("#due_date_duration").val();
                    let saleDateValue = $("#purchase_date").val();

                    if (!saleDateValue) {
                        e.preventDefault();
                        $("#purchase_date_error").removeClass("hidden");
                    } else if (!dueDateDuration) {
                        e.preventDefault();
                        $("#due_date_error").removeClass("hidden");
                    } else {
                        $("#purchase_date_error").addClass("hidden");
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

                $('#supplier_id').select2();

                $('#supplier_id').on('change', function() {
                    let supplierId = $(this).val();
                    $('.item-select').each(function() {
                        let row = $(this).closest('tr');
                        let itemId = $(this).val();
                        updateItemData(row, itemId, supplierId);
                    });
                });

                $('.item-select').each(function() {
                    let row = $(this).closest('tr');
                    let itemId = $(this).val();
                    let supplierId = $('#supplier_id').val();

                    if (itemId && supplierId) {
                        updateItemData(row, itemId, supplierId);
                    }
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
                        .val(formatRupiah(originalTotal))
                        .attr('data-total', originalTotal.toFixed(2));
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
                    let totalPriceBeforeTax = subTotal - totalDiscount;

                    $('#sub_total').val(formatRupiah(subTotal));
                    $('#total_discount1').val(formatRupiah(totalDiscount1));
                    $('#total_discount2').val(formatRupiah(totalDiscount2));
                    $('#total_discount3').val(formatRupiah(totalDiscount3));

                    $('#total_price').attr('data-total', totalPriceBeforeTax.toFixed(2));

                    calculateTotalPrice();
                }

                function calculateTotalPrice() {
                    let totalPriceBeforeTax = parseFloat($('#total_price').attr('data-total')) || 0;
                    let taxType = $('#tax').val();
                    let taxRate = (taxType === 'ppn') ? 0.11 : 0;
                    let taxAmount = totalPriceBeforeTax * taxRate;
                    let finalTotalPrice = totalPriceBeforeTax + taxAmount;

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

                    let selectedItems = $('.item-select').map(function() {
                        return $(this).val();
                    }).get();

                    let row = `
                        <tr class="border-b border-gray-300">
                                <td class="px-1 py-2">
                                    <input type="text" class="item-code w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly>
                                </td>
                                <td>
                                    <select name="items[]" class="item-select w-full select2 px-2 py-1 border border-gray-300 rounded-md">
                                    <option value="">Pilih Barang</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                    </select>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="number" name="stock[]" class="stock w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text" name="unit[]" class="unit w-16 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center" readonly>
                                </td>
                                <td class="px-1 py-2">
                                    <input type="number" name="qty[]" class="qty w-16 px-2 py-1 border border-gray-300 rounded-md text-center" min="1" value="1" required data-parsley-required-message="Jumlah harus diisi">
                                </td>
                                <td class="px-1 py-2">
                                    <input type="text" name="price_per_item[]" class="price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly>
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
                                    <input type="text" name="prices[]" class="total-price w-40 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right" readonly>
                                </td>
                                <td class="px-1 py-2 text-center">
                                    <button type="button" class="remove-item px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        `;

                    $('#items-table tbody').append(row);
                    $('.item-select').each(function() {
                        if (!$(this).data('locked')) {
                            $(this).select2();
                        }
                    });

                    disableSelectedItems();
                });

                function disableSelectedItems() {
                    let selectedItems = $('.item-select').map(function() {
                        return $(this).val();
                    }).get();

                    $('.item-select').each(function() {
                        let select = $(this);
                        select.find('option').each(function() {
                            let optionValue = $(this).val();
                            if (selectedItems.includes(optionValue) && optionValue !== '' &&
                                optionValue !== select.val()) {
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
                    let purchaseId = $('#purchase_id').val();

                    calculateSubTotal();
                    calculateTotalQty();

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
                                url: `/purchase-edit/${purchaseId}/item-delete/${itemId}`, // Endpoint untuk delete
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
                                }
                            });
                        }
                    });
                });

                $('#edit-form').parsley();

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

            .duration-btn:disabled {
                background-color: #a0aec0;
                cursor: not-allowed;
                opacity: 0.6;
            }
        </style>
    @endpush
</x-app-layout>
