<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">{{ __('Retur Pembelian ' . $purchase->purchase_number) }}</h2>
    </x-slot>

    <form action="{{ route('manager.purchase.store') }}" method="POST">
        @csrf
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-form.label for="purchase_number" :value="__('Nomor Pembelian')" />
                    <input id="purchase_number" class="block w-full border-gray-300 rounded-md bg-gray-100" type="text"
                        name="purchase_number" value="{{ $purchase->purchase_number }}" readonly />

                    <x-form.label for="purchase_date" :value="__('Tanggal Pembelian')" />
                    <input id="purchase_date" class="block w-full border-gray-300 rounded-md bg-gray-100" type="date"
                        name="purchase_date" value="{{ $purchase->purchase_date }}" readonly />

                    <x-form.label for="supplier_name" :value="__('Supplier')" />
                    <input id="supplier_name" class="block w-full border-gray-300 rounded-md bg-gray-100" type="text"
                        name="supplier_name" value="{{ $purchase->supplier->contact }}" readonly />

                    <x-form.label for="tax" :value="__('Pajak')" />
                    <input id="tax" class="block w-full border-gray-300 rounded-md bg-gray-100" type="text"
                        name="tax" value="{{ $purchase->tax_type == 'ppn' ? 'PPN 11%' : 'NON-PPN' }}" readonly />
                </div>

                <div>
                    <x-form.label for="information" :value="__('Keterangan')" />
                    <textarea id="information" name="information"
                        class="w-full border-gray-400 rounded-md bg-gray-100 focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                        rows="3" placeholder="Deskripsi barang" readonly>{{ old('information', $purchase->information) }}</textarea>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <h3 class="text-lg font-semibold">{{ __('Barang') }}</h3>
            <hr class="my-2 border-gray-300">
        </div>

        <div class="mt-5 space-y-2">
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
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-300">
                            @foreach ($purchase->items as $index => $item)
                                <tr class="border-b border-gray-300">
                                    <td class="px-1 py-1">
                                        <input type="text"
                                            class="item-code w-32 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->code }}">
                                    </td>
                                    <td>
                                        <input type="text" name="items[]"
                                            class="item-name w-64 px-1 py-1 border border-gray-300 rounded-md bg-gray-100"
                                            readonly value="{{ $item->name }}">
                                    </td>
                                    <td class="px-1 py-1">
                                        <input type="number" name="stock[]"
                                            class="stock w-20 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->stock }}">
                                    </td>
                                    <td class="px-1 py-1">
                                        <input type="text" name="unit[]"
                                            class="unit w-20 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->unit }}">
                                    </td>
                                    <td class="px-1 py-1">
                                        <input type="number" name="qty[]"
                                            class="qty w-20 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-center"
                                            readonly value="{{ $item->pivot->qty }}">
                                    </td>
                                    <td class="px-1 py-1">
                                        <input type="text" name="prices[]"
                                            class="price w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                            readonly value="{{ $item->purchase_price }}">
                                    </td>
                                    <td class="px-1 py-1">
                                        <div class="flex space-x-1">
                                            <input type="text" name="discount1[]"
                                                class="discount1 w-10 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                                readonly placeholder="D1" value="{{ $item->pivot->discount1 }}">
                                            <input type="text" name="discount2[]"
                                                class="discount2 w-10 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                                readonly placeholder="D2" value="{{ $item->pivot->discount2 }}">
                                            <input type="text" name="discount3[]"
                                                class="discount3 w-10 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                                readonly placeholder="D3" value="{{ $item->pivot->discount3 }}">
                                            <input type="text" name="ad[]"
                                                class="ad w-8 px-1 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                                readonly placeholder="AD">
                                        </div>
                                    </td>
                                    <td class="px-1 py-1">
                                        <input type="text" name="price_per_item[]"
                                            class="total-price w-40 px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                            readonly
                                            value="{{ number_format($item->pivot->price_per_item, 2, '.', '') }}">
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

    <form action="{{ route('manager.return.purchase.create', $purchase->id) }}" method="POST">
        @csrf
        <div class="p-6 bg-white rounded-md shadow-md">
            <div class="space-y-2">
                <x-form.label for="reason" :value="__('Alasan Retur')" />
                <textarea id="reason" name="reason"
                    class="w-full border-gray-400 rounded-md focus:ring focus:ring-purple-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300"
                    rows="3" required></textarea>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold">{{ __('Pilih Barang untuk Diretur') }}</h3>
                <hr class="my-2 border-gray-300">
            </div>

            <div class="mt-5 space-y-2">
                <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-lg shadow-md">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-max border border-gray-300 shadow-md table-auto">
                            <thead
                                class="bg-gray-200 text-gray-700 uppercase text-sm tracking-wider sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-2 text-center border-b border-gray-300">Nama Barang</th>
                                    <th class="px-4 py-2 text-center border-b border-gray-300">Qty</th>
                                    <th class="px-4 py-2 text-center border-b border-gray-300">Harga</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-300">
                                @foreach ($purchase->items as $item)
                                    <tr class="border-b border-gray-300">
                                        <td class="px-4 py-2 text-center">{{ $item->name }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="number" name="qty[]" max="{{ $item->pivot->qty }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded-md text-center"
                                                required>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <input type="hidden" name="items[]" value="{{ $item->id }}">
                                            <input type="text" name="price_per_item[]"
                                                value="{{ $item->pivot->price_per_item }}"
                                                class="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-right"
                                                readonly>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid justify-items-end mt-4 space-y-2">
                <x-button class="gap-2">
                    <span>{{ __('Submit Retur') }}</span>
                </x-button>
            </div>
        </div>
    </form>
</x-app-layout>
