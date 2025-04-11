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
                url: '/outgoingpayment-data?page=' + page + '&search=' + searchQuery,
                method: 'GET',
                success: function(response) {
                    let rows = '';

                    if (response.data.length === 0) {
                        rows = `
                            <tr>
                                <td colspan="7" class="py-3 px-6 text-center">Data tidak ditemukan</td>
                            </tr>
                            `;
                    } else {
                        $.each(response.data, function(index, purchase) {
                            rows += `
                            <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="/manager/outgoingpayment/${purchase.id}" class="text-blue-500 hover:underline">${purchase.purchase_number}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${purchase.purchase_date}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${purchase.supplier.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${formatRupiah(purchase.total_price)}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${purchase.status === 'belum_lunas' ? 'Belum Lunas' : purchase.status === 'lunas' ? 'Lunas' : purchase.status}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-button target="" href="/manager/outgoingpayment/${purchase.id}" variant="primary" size="sm" class="justify-center max-w-sm gap-2">
                                        Lihat
                                    </x-button>

                                    <button onclick="toggleDetails(${index})" class="bg-green-800 text-white  p-2 rounded sm:hidden">
                                        <x-heroicon-o-chevron-down class="w-2 h-2" aria-hidden="true" />    
                                    </button>
                                </td>
                            </tr>
                            <tr id="details-${index}" class="hidden sm:hidden">
                                <td colspan="6" class="px-6 py-4">
                                    <div>
                                        <p><strong>Kode:</strong> ${purchase.code}</p>
                                        <p><strong>Harga/pcs:</strong> ${formatRupiah(purchase.price)}</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        });
                    }

                    $('#outpaymentTable').html(rows);

                    lastPage = response.last_page;
                    $('#currentPage').text(page);

                    $('#nextPage').attr('disabled', page >= lastPage);
                    $('#prevPage').attr('disabled', page <= 1);
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
