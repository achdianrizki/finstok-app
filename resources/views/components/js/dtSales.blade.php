<script>
    $(document).ready(function() {
        let page = 1;
        let lastPage = 1;
        let searchQuery = '';

        function fetchItems(page, searchQuery = '') {

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(number);
            }

            $.ajax({
                url: '/sales-data?page=' + page + '&search=' + searchQuery,
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

                            let duedate = new Date(sale.due_date);
                            let formattedDueDate = date.getDate().toString().padStart(2,
                                    '0') +
                                '-' +
                                (duedate.getMonth() + 1).toString().padStart(2, '0') + '-' +
                                duedate.getFullYear();

                            rows += `
                            <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td class="px-6 py-4 whitespace-nowrap">${sale.sale_number}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${formattedDate}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${formattedDueDate}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${sale.buyer.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${sale.status === 'belum_lunas' ? 'Belum Lunas' : sale.status === 'lunas' ? 'Lunas' : sale.status}</td>
                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="flex items-center gap-2">
                                    <a href="report/sale-invoice/export/pdf/${sale.id}"
                                            class="flex items-center  text-sm text-white bg-red-500 hover:bg-red-600 w-20 px-2 py-1 border rounded-md"
                                            role="menuitem" tabindex="-1" id="menu-item-0">
                                            <x-icons.pdf class="w-5 h-5" aria-hidden="true" />
                                            <span>Bukti</span>
                                    </a>

                                    <x-button target="" href="/manager/sales/${sale.id}/edit" variant="warning" class="justify-center max-w-sm gap-2">
                                        <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                    </x-button>

                                    <form method="POST" action="/manager/sales/${sale.id}" class="delete-form" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <x-button type="button" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 delete-button">
                                            <x-heroicon-o-trash class="w-3 h-3" aria-hidden="true" />
                                        </x-button>
                                    </form>
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

                    generatePaginationButtons(page, lastPage);

                    $('#nextPage').attr('disabled', page >= lastPage);
                    $('#prevPage').attr('disabled', page <= 1);

                    // Tambahkan event listener untuk tombol hapus
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
                                form.submit();
                            }
                        });
                    });
                }
            });
        }

        fetchItems(page);

        $('#nextPage').on('click', function() {
            if (page < lastPage) {
                page++;
                fetchItems(page, searchQuery);
            }
        });

        $('#prevPage').on('click', function() {
            if (page > 1) {
                page--;
                fetchItems(page, searchQuery);
            }
        });

        $('#search').on('keyup', function() {
            searchQuery = $(this).val();
            page = 1;
            fetchItems(page, searchQuery);
        });

        function generatePaginationButtons(page, lastPage) {
            let paginationButtons = '';
            let maxButtons = window.innerWidth <= 640 ? 5 : 10;
            let half = Math.floor(maxButtons / 2);

            let startPage = Math.max(1, page - half);
            let endPage = Math.min(lastPage, page + half);

            if (endPage - startPage + 1 < maxButtons) {
                if (startPage === 1) {
                    endPage = Math.min(lastPage, startPage + maxButtons - 1);
                } else if (endPage === lastPage) {
                    startPage = Math.max(1, endPage - maxButtons + 1);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationButtons += `
            <button class="pagination-btn ${i === page ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700'} px-3 py-1 rounded hover:bg-purple-500 hover:text-white" data-page="${i}">
                ${i}
            </button>
        `;
            }

            $('#paginationNumbers').html(paginationButtons);
        }


        $(document).on('click', '.pagination-btn', function() {
            page = parseInt($(this).data('page'));
            fetchItems(page, searchQuery);
        });

        $(window).on('resize', function() {
            generatePaginationButtons(page, lastPage);
        });
    });
</script>
