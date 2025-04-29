<script>
    $(document).ready(function() {
        let page = 1;
        let lastPage = 1;
        let searchQuery = '';
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        function fetchitems(page, searchQuery = '') {
            $.ajax({
                url: '/supplier-data?page=' + page + '&search=' + searchQuery,
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
                        $.each(response.data, function(index, supplier) {
                            rows += `
                                <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900 ${supplier.status == 0 ? 'bg-red-200' : ''}">
                                    <td class="px-6 py-4 whitespace-nowrap">${supplier.supplier_code}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${supplier.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${supplier.address}</td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${supplier.city}</td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${supplier.province}</td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">${(supplier.phone == null) ? '-' : supplier.phone}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-button target="" href="/manager/supplier/${supplier.id}/edit" variant="warning" class="justify-center max-w-sm gap-2">
                                            <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                        </x-button>
                                        
                                        <form method="POST" action="/manager/supplier/${supplier.id}" style="display:inline;" onsubmit="return confirmDelete(event)">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <x-button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">
                                                <x-heroicon-o-trash class="w-3 h-3" aria-hidden="true" />
                                            </x-button>
                                        </form>
                                    </td>
                                </tr>
                                <tr id="details-${index}" class="hidden sm:hidden">
                                    <td colspan="8" class="px-6 py-4">
                                        <div>
                                            <p><strong>Alamat:</strong> ${supplier.address}</p>
                                            <p><strong>Kota:</strong> ${supplier.city}</p>
                                            <p><strong>Provinsi:</strong> ${supplier.province}</p>
                                            <p><strong>Nama Kontak:</strong> ${supplier.contact}</p>
                                            <p><strong>Nomor Telepon:</strong> ${supplier.phone}</p>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    $('#supplierTable').html(rows);
                    lastPage = response.last_page || 1;
                    $('#currentPage').text(page);

                    generatePaginationButtons(page, lastPage);

                    $('#nextPage').attr('disabled', page >= lastPage);
                    $('#prevPage').attr('disabled', page <= 1);
                }
            });
        }

        fetchitems(page);

        $(document).on('click', '.toggle-details', function() {
            let index = $(this).data('index');
            $('#details-' + index).toggleClass('hidden');
        });

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

        let searchTimeout;
        $('#search').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchQuery = $(this).val();
            searchTimeout = setTimeout(() => {
                page = 1;
                fetchitems(page, searchQuery);
            }, 500);
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

    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
    }
</script>
