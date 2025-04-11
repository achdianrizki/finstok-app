<script>
    $(document).ready(function() {
        let page = 1;
        let lastPage = 1;
        let searchQuery = '';

        function fetchwarehouses(page, searchQuery = '') {

            $.ajax({
                url: '/warehouses-data?page=' + page + '&search=' + searchQuery,
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
                        $.each(response.data, function(index, warehouse) {
                            rows += `
                          <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td class="px-6 py-4 whitespace-nowrap">${warehouse.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${warehouse.address}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-button target="" href="/manager/warehouses/${warehouse.slug}/edit" variant="warning" class="justify-center max-w-sm gap-2">
                                        <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                    </x-button>
                                    <!-- Destroy form -->
                                        <form action="/manager/warehouses/${warehouse.slug}" method="POST" class="inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <x-button variant="danger" type="submit" class="justify-center max-w-sm gap-2">
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
                                        <p><strong>Alamat:</strong> ${warehouse.address}</p>
                                    </div>
                                </td>
                            </tr>
                      `;
                        });


                    }
                    $('#itemTable').html(rows);

                    lastPage = response.last_page;

                    $('#currentPage').text(page);

                    if (page >= lastPage) {
                        $('#nextPage').attr('disabled', true);
                    } else {
                        $('#nextPage').attr('disabled', false);
                    }

                    if (page <= 1) {
                        $('#prevPage').attr('disabled', true);
                    } else {
                        $('#prevPage').attr('disabled', false);
                    }

                    // Tambahkan event listener untuk konfirmasi SweetAlert
                    $('.delete-form').on('submit', function(e) {
                        e.preventDefault();
                        let form = this;
                        let warehouseId = $(form).attr('action').split('/').pop();

                        $.ajax({
                            url: '/check-warehouse-items/' + warehouseId,
                            method: 'GET',
                            success: function(response) {
                                if (response.items.length > 0) {
                                    Swal.fire({
                                        title: 'Peringatan!',
                                        text: 'Gudang masih memiliki item terkait. Terdapat ' + response.items.length + ' item. Anda tidak dapat menghapus gudang ini.',
                                        icon: 'warning',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            form.submit();
                                        }
                                    });
                                } else {
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
                                            form.submit();
                                        }
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Gagal memeriksa data terkait.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    });
                }
            });
        }

        fetchwarehouses(page);

        $('#nextPage').on('click', function() {
            if (page < lastPage) {
                page++;
                fetchwarehouses(page, searchQuery);
            }
        });

        $('#prevPage').on('click', function() {
            if (page > 1) {
                page--;
                fetchwarehouses(page, searchQuery);
            }
        });

        $('#search').on('keyup', function() {
            searchQuery = $(this).val();
            page = 1;
            fetchwarehouses(page, searchQuery);
        });
    });
</script>