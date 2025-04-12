<script>
    $(document).ready(function() {
        let page = 1;
        let lastPage = 1;
        let searchQuery = '';

        function fetchitems(page, searchQuery = '') {

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(number);
            }

            $.ajax({
                url: '/mutation-data?page=' + page + '&search=' + searchQuery,
                method: 'GET',
                success: function(response) {
                    let rows = '';

                    if (response.data.length === 0) {
                        rows = `
                    <tr>
                        <td colspan="6" class="py-3 px-6 text-center">Data tidak ditemukan</td>
                    </tr>
                    `;
                    } else {
                        $.each(response.data, function(index, item) {
                            rows += `
                            <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td class="px-6 py-4 whitespace-nowrap">${item.id}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.mutation_date}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.source_warehouse}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.destination_warehouse}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.total_items}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!-- <x-button target="" href="/manager/items/${item.id}/edit" variant="warning" class="justify-center max-w-sm gap-2">
                                        <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                    </x-button> --!>
                                    <form method="POST" action="/mutation/delete/${item.id}" class="delete-form" style="display:inline;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <x-button type="button" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 delete-button">
                                            <x-heroicon-o-trash class="w-3 h-3" aria-hidden="true" />
                                        </x-button>
                                    </form>

                                    <button onclick="toggleDetails(${index})" class="bg-green-800 text-white  p-2 rounded sm:hidden">
                                        <x-heroicon-o-chevron-down class="w-2 h-2" aria-hidden="true" />    
                                    </button>
                                </td>
                            </tr>
                            <tr id="details-${index}" class="hidden sm:hidden">
                                <td colspan="6" class="px-6 py-4">
                                    <div>
                                        <p><strong>Nama Barang:</strong> ${item.name}</p>
                                        <p><strong>Kategori:</strong> ${item.category}</p>
                                        <p><strong>Satuan:</strong> ${item.unit}</p>
                                        <p><strong>Harga:</strong> ${formatRupiah(item.price)}</p>
                                        <p><strong>Deskripsi:</strong> ${item.description}</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        });
                    }

                    $('#itemTable').html(rows);

                    lastPage = response.last_page;
                    $('#currentPage').text(page);

                    $('#nextPage').attr('disabled', page >= lastPage);
                    $('#prevPage').attr('disabled', page <= 1);

                    $('.delete-button').on('click', function(e) {
                        e.preventDefault();
                        let form = $(this).closest('form');
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log("berhasil");
                                form.submit();
                            }
                        });
                    });
                }
            });
        }

        fetchitems(page);

        $('#nextPage').on('click', function() {
            if (page < lastPage) {
                page++;
                fetchitems(page, searchQuery);
            }
        });

        $('#prevPage').on('click', function() {
            if (page > 1) {
                page--;
                fetchitems(page, searchQuery);
            }
        });

        $('#search').on('keyup', function() {
            searchQuery = $(this).val();
            page = 1;
            fetchitems(page, searchQuery);
        });
    });
</script>