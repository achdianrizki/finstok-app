<script>
    $(document).ready(function() {
        let page = 1;
        let lastPage = 1;
        let searchQuery = '';

        function fetchitems(page, searchQuery = '') {

            $.ajax({
                url: '/report-return-purchase-items?page=' + page + '&search=' + searchQuery,
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
                        $.each(response.data, function(index, returnPurchase) {
                            const returnDate = new Date(returnPurchase.return_date);
                            const formattedDate = returnDate.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });
                            rows += `
                              <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td class="px-6 py-4 whitespace-nowrap">${ returnPurchase.purchase_number }</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${formattedDate}</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${ returnPurchase.supplier_name }</td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${ returnPurchase.qty }</td>
                              </tr>
                                  <tr id="details-${index}" class="hidden sm:hidden">
                                          <td colspan="6" class="px-6 py-4">
                                                  <div>
                                                          <p><strong>No Pembelian:</strong> ${returnPurchase.purchase_number}</p>
                                                          <p><strong>Jumlah Retur:</strong> ${returnPurchase.qty}</p>
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
            fetchitems(page, searchQuery);
        });

        $(window).on('resize', function() {
            generatePaginationButtons(page, lastPage);
        });
    });
</script>
