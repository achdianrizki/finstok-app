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
                url: '/items-purchase-data?page=' + page + '&search=' + searchQuery,
                method: 'GET',
                success: function(response) {
                    let rows = '';

                    if (response.data.length === 0) {
                        rows = `
                    <tr>
                        <td colspan="6" class="py-3 px-6 text-center">Not Found</td>
                    </tr>
                    `;
                    } else {
                        $.each(response.data, function(index, item) {
                        rows += `
                            <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td class="px-6 py-4 whitespace-nowrap">${item.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${item.code}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${item.stok}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${formatRupiah(item.price)}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">${item.category ? item.category.name : 'No Category'}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">${item.warehouse ? item.warehouse.name : 'No Warehouse'}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-button target="" href="/manager/items/${item.id}/edit" variant="warning" class="justify-center max-w-sm gap-2">
                                        <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                    </x-button>
                                    <x-button target="" href="{{ route('manager.items.create') }}" variant="danger" class="justify-center max-w-sm gap-2">
                                        <x-heroicon-o-trash class="w-3 h-3" aria-hidden="true" />
                                    </x-button>
                                    <button onclick="toggleDetails(${index})" class="bg-green-800 text-white  p-2 rounded sm:hidden">
                                        <x-heroicon-o-chevron-down class="w-2 h-2" aria-hidden="true" />    
                                    </button>
                                </td>
                            </tr>
                            <tr id="details-${index}" class="hidden sm:hidden">
                                <td colspan="6" class="px-6 py-4">
                                    <div>
                                        <p><strong>Kode:</strong> ${item.code}</p>
                                        <p><strong>Stok:</strong> ${item.stok}</p>
                                        <p><strong>Harga/pcs:</strong> ${formatRupiah(item.price)}</p>
                                        <p><strong>Kategori:</strong> ${item.category ? item.category.name : 'No Category'}</p>
                                        <p><strong>Gudang:</strong> ${item.warehouse ? item.warehouse.name : 'No Warehouse'}</p>
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
