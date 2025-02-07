<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-button target="" href="{{ route('manager.items.index') }}" variant="primary" size="sm"
                class="justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-4 h-4" aria-hidden="true" />
            </x-button>
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Tambah Data Pembelian') }}
            </h2>
        </div>
    </x-slot>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('manager.purchase.store') }}" method="POST">
            @csrf
            {{-- {{ dd("halo") }} --}}
            <div class="grid gap-2">
                <div class="mb-5 space-y-2">
                    <x-form.label for="item_id" :value="__('Nama Barang')" />
                    <select id="item_id" name="item_id" class="block w-full select2">
                        <option value=""></option>
                    </select>
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="price" :value="__('Harga/PCS')" />
                    <x-form.input id="price" class="block w-full" type="number" inputmode="numeric"
                        name="price" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="qty" :value="__('Jumlah')" />
                    <x-form.input id="qty" class="block w-full" type="number" inputmode="numeric" name="qty"
                        placeholder="{{ __('Jumlah Barang') }}" />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="total_price" :value="__('Total Harga')" />
                    <x-form.input id="total_price_dsp" class="block w-full" type="text" readonly />
                    <x-form.input id="total_price" class="block w-full" type="hidden" name="total_price" readonly />
                </div>

                <div class="mb-5 space-y-2">
                    <x-form.label for="supplier_name" :value="__('Nama Supplier')" />
                    <x-form.input id="supplier_name" class="block w-full" type="text" name="supplier_name"
                        placeholder="{{ __('Nama Supplier') }}" />
                </div>

                <div class="grid justify-items-end">
                    <x-button class="gap-2" id="buttonSubmit">
                        <span>{{ __('Submit') }}</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>

    @php
        $button =
            "<p class='text-xs'>Tidak ada barang yang di cari klik <a href='" .
            route('manager.items.create') .
            "' class='px-4 py-2 bg-green-500 text-white rounded-md inline-flex items-center gap-2 text-xs'>
                   <span>Tambah Barang</span>
               </a>  untuk menambahkan barang </p>";
    @endphp

    @push('scripts')
        <script>
            $(document).ready(function() {
                let select = $('#item_id');

                select.select2({
                    placeholder: "Cari Nama Barang...",
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return ` {!! $button !!}`;
                        }
                    },
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });

                $.get('/get-items', function(data) {
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            select.append(
                                `<option value="${item.id}">${item.name}</option>`
                            );
                        });
                    }
                });

                select.on('change', function() {
                    let selectedOption = $(this).find(':selected');
                    calculateTotal();
                    calculateOrigin();

                });

                $('#qty').on('input', function() {
                    calculateTotal();
                    calculateOrigin();
                });

                function calculateTotal() {
                    let price = parseFloat($('#price').val()) || 0;
                    let qty = parseFloat($('#qty').val()) || 0;
                    let total = price * qty;
                    $('#total_price_dsp').val(total.toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }));
                }

                function calculateOrigin() {
                    let price = parseFloat($('#price').val()) || 0;
                    let qty = parseFloat($('#qty').val()) || 0;
                    let total = price * qty;

                    $('#total_price').val(total.toFixed(0));
                }

            });
        </script>
    @endpush



    @push('styles')
        <style>
            .select2-container .select2-selection--single {
                height: 42px !important;
                border-radius: 5px;
                border: 1px solid #9CA3AF;
                padding: 8px;
            }

            .select2-container .select2-selection--single .select2-selection__rendered {
                font-size: 14px;
                color: #374151;
            }

            .select2-container .select2-selection--single .select2-selection__arrow {
                height: 42px !important;
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
