<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                @section('title', __('Sampah Pembelian Barang'))
                {{ __('Sampah Pembelian Barang') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex flex-col md:flex-row md:justify-end gap-4 my-3">
            <div class="w-full md:w-auto">
                <input type="text" id="search" placeholder="Search items..."
                    class=" rounded w-full md:w-auto px-4 py-2 dark:bg-dark-eval-1" name="search">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="export-table" class="min-w-full rounded-md">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 dark:bg-slate-900 dark:text-white text-sm leading-normal">
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nomor Pembelian
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                            Tanggal Pembelian</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden md:table-cell">
                            Nama Pemasok</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider hidden sm:table-cell">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-dark-eval-1"
                    id="itemTable">
                    @if ($deletedPurchases->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center">
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    @else
                        @foreach ($deletedPurchases as $purchase)
                            <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $purchase->purchase_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                    {{ $purchase->purchase_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                    {{ $purchase->supplier->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                    {{ $purchase->status === 'belum_lunas' ? 'Belum Lunas' : 'Lunas' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('manager.trash.purchase.restore', $purchase->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <x-button type="submit" size="sm"
                                                class="text-blue-600 hover:underline">
                                                <x-heroicon-o-arrow-path class="w-5 h-5" aria-hidden="true" />
                                            </x-button>
                                        </form>
                                        <form data-id="{{ $purchase->id }}">
                                            <x-button type="button" size="sm" variant="danger"
                                                class="delete-button" :disabled="$purchase->items->count() > 0">
                                                <x-heroicon-o-x-circle class="w-5 h-5" aria-hidden="true" />
                                            </x-button>
                                        </form>

                                        <x-button type="button" size="sm" class="lihat-barang-btn"
                                            data-id="{{ $purchase->id }}">
                                            Lihat Barang
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div id="itemModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative">
                <h2 class="text-lg font-semibold mb-4" id="purchaseNumber">Daftar Barang</h2>
                <table class="w-full mb-4">
                    <thead>
                        <tr>
                            <th class="text-left">Nama</th>
                            <th class="text-left">Jumlah</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="item-list"></tbody>
                </table>
                <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Tutup
                </button>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#search').on('keyup', function() {
                    var value = $(this).val().toLowerCase();
                    $('#itemTable tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });
            });
        </script>

        <script>
            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();
                const purchaseId = $(this).closest('form').data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/manager/trash/purchase/forcedelete/${purchaseId}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Data berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        </script>

        <script>
            function closeModal() {
                $('#itemModal').addClass('hidden');
            }

            $(document).on('click', '.lihat-barang-btn', function() {
                let purchaseId = $(this).data('id');
                $.ajax({
                    url: `/restore/${purchaseId}/items`,
                    type: 'GET',
                    success: function(data) {
                        let rows = '';
                        if (data.length === 0) {
                            rows = '<tr><td colspan="3" class="text-center">Tidak ada barang</td></tr>';
                        } else {
                            data.forEach(item => {
                                rows += `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.qty}</td>
                                <td>
                                    <button class="text-red-500 delete-item-btn" data-id="${item.id}" data-purchase-id="${purchaseId}">Hapus</button>
                                </td>
                            </tr>
                        `;
                            });
                        }
                        $('#item-list').html(rows);
                        $('#itemModal').removeClass('hidden');
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal mengambil data barang', 'error');
                    }
                });
            });

            $(document).on('click', '.delete-item-btn', function() {
                const item_id = $(this).data('id');
                const purchase_id = $(this).data('purchase-id');
                const button = $(this);

                console.log(item_id, purchase_id);

                Swal.fire({
                    title: 'Hapus Barang?',
                    text: 'Barang akan dihapus dari pembelian ini.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/purchase/${purchase_id}/item/${item_id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                button.closest('tr').remove();
                                Swal.fire('Berhasil', 'Barang berhasil dihapus', 'success');
                            }
                        });
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
