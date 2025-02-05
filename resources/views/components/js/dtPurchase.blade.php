<script>
    $(document).ready(function() {
        let page = 1;
        let lastPage = 1;
        let searchQuery = '';

        function fetchpurchases(page, searchQuery = '') {

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(number);
            }

            function formatDate(dateString) {
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit'
                };
                return new Date(dateString).toLocaleDateString('id-ID', options).replace(/\//g, '-');
            }

            $.ajax({
                url: '/purchases-data?page=' + page + '&search=' + searchQuery,
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
                        $.each(response.data, function(index, purchase) {
                            rows += `
                            <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td class="px-6 py-4 whitespace-nowrap">${purchase.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${purchase.purchase_type === 'asset' ? purchase.qty : purchase.item.stok }</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${formatRupiah(purchase.price)}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${formatRupiah(purchase.total_price)}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${purchase.supplier_name}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell" >${formatDate(purchase.created_at)}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${purchase.purchase_type === 'asset' ? 
                                    `<x-button target="" href="/manager/purchases/${purchase.id}/edit" variant="warning" class="justify-center max-w-sm gap-2">
                                        <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                    </x-button>
                                    <form method="POST" action="/manager/purchases/${purchase.id}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <x-button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">
                                            <x-heroicon-o-trash class="w-3 h-3" aria-hidden="true" />
                                        </x-button>
                                    </form>`
                                    :

                                    'ini barang' }

                                    <button onclick="toggleDetails(${index})" class="bg-green-800 text-white p-2 rounded md:hidden sm:hidden">
                                        <x-heroicon-o-chevron-down class="w-2 h-2" aria-hidden="true" />    
                                    </button>
                                </td>
                            </tr>
                            <tr id="details-${index}" class="hidden sm:hidden">
                                <td colspan="6" class="px-6 py-4">
                                    <div>
                                        <p><strong>Stok/Qty:</strong>${purchase.purchase_type = 'asset' ? purchase.qty : purchase.item.stok }</p>
                                        <p><strong>Harga/pcs:</strong> ${formatRupiah(purchase.price)}</p>
                                        <p><strong>Total:</strong>${formatRupiah(purchase.total_price)}</p>
                                        <p><strong>Supplier:</strong> ${purchase.supplier_name}</p>
                                        <p><strong>Tgl Barang Msk:</strong>${formatDate(purchase.created_at)}</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        });
                    }

                    $('#purchaseTable').html(rows);

                    lastPage = response.last_page;
                    $('#currentPage').text(page);

                    $('#nextPage').attr('disabled', page >= lastPage);
                    $('#prevPage').attr('disabled', page <= 1);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                }
            });
        }

        fetchpurchases(page);

        $('#nextPage').on('click', function() {
            if (page < lastPage) {
                page++;
                fetchpurchases(page, searchQuery);
            }
        });

        $('#prevPage').on('click', function() {
            if (page > 1) {
                page--;
                fetchpurchases(page, searchQuery);
            }
        });

        $('#search').on('keyup', function() {
            searchQuery = $(this).val();
            page = 1;
            fetchpurchases(page, searchQuery);
        });
    });
</script>
