<script>
    $(document).ready(function() {
        let page = 1;
        let lastPage = 1;
        let searchQuery = '';
        let period = '';
        let startDate = '';
        let endDate = '';

        function fetchItems(page, searchQuery = '', period = '', startDate = '', endDate = '') {

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(number);
            }

            $.ajax({
                url: '/sales-data?page=' + page + '&search=' + searchQuery + '&period=' + period +
                    '&start_date=' + startDate + '&end_date=' + endDate,
                method: 'GET',
                success: function(response) {
                    let rows = '';

                    if (response.data.length === 0) {
                        rows = `
                  <tr>
                      <td colspan="6" class="py-3 text-center">Data tidak ditemukan</td>
                  </tr>
                  `;
                    } else {
                        $.each(response.data, function(index, sale) {
                            let date = new Date(sale.sale_date);
                            let formattedDate = date.getDate().toString().padStart(2, '0') +
                                '-' +
                                (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                                date.getFullYear();

                            rows += `
                            <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900 ${sale.status === 'belum_lunas' ? 'bg-red-100' : ''}">
                                <td class="px-6 py-4 whitespace-nowrap">${sale.sale_number}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${formattedDate}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${sale.buyer.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${sale.status === 'belum_lunas' ? 'Belum Lunas' : sale.status === 'lunas' ? 'Lunas' : sale.status}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="sale-invoice/export/pdf/${sale.id}"
                                            class="flex items-center  text-sm text-white bg-red-500 hover:bg-red-600 w-20 px-2 py-1 border rounded-md"
                                            role="menuitem" tabindex="-1" id="menu-item-0">
                                            <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                                            <span>Bukti</span>
                                    </a>
                                   <!-- <x-button target="" href="/manager/sales/${sale.id}/edit" variant="warning" class="justify-center max-w-sm gap-2">
                                        <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                    </x-button> !-->
                                    <!--<form method="POST" action="/manager/sales/${sale.id}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <x-button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">
                                            <x-heroicon-o-trash class="w-3 h-3" aria-hidden="true" />
                                        </x-button> 
                                    </form>!-->
                                    </div>

                                    <button onclick="toggleDetails(${index})" class="bg-green-800 text-white  p-2 rounded sm:hidden">
                                        <x-heroicon-o-chevron-down class="w-2 h-2" aria-hidden="true" />    
                                    </button>
                                </td>
                            </tr>
                            <tr id="details-${index}" class="hidden sm:hidden">
                                <td colspan="6" class="px-6 py-4">
                                    <div>
                                        <p><strong>Kode:</strong> ${sale.code}</p>
                                        <p><strong>Harga/pcs:</strong> ${formatRupiah(sale.price)}</p>
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
                }
            });
        }

        fetchItems(page);

        $('#nextPage').on('click', function() {
            if (page < lastPage) {
                page++;
                fetchItems(page, searchQuery, period, startDate, endDate);
            }
        });

        $('#prevPage').on('click', function() {
            if (page > 1) {
                page--;
                fetchItems(page, searchQuery, period, startDate, endDate);
            }
        });

        $('#search').on('keyup', function() {
            searchQuery = $(this).val();
            page = 1;
            fetchItems(page, searchQuery, period, startDate, endDate);
        });

        $('#period').on('change', function() {
            period = $(this).val();
            if (period === 'custom') {
                $('.custom').removeClass('hidden');
            } else {
                $('.custom').addClass('hidden');
                $('#start_date, #end_date').val('');
                startDate = '';
                endDate = '';
            }
            page = 1;
            fetchItems(page, searchQuery, period, startDate, endDate);
        });

        $('#start_date, #end_date').on('change', function() {
            startDate = $('#start_date').val();
            endDate = $('#end_date').val();
            fetchItems(page, searchQuery, period, startDate, endDate);
        });
    });
</script>
