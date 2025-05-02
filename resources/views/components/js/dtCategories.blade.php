<script>
    $(document).ready(function() {
        let page = 1;
        let lastPage = 1;
        let searchQuery = '';

        function fetchcategories(page, searchQuery = '') {

            $.ajax({
                url: '/categories-data?page=' + page + '&search=' + searchQuery,
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
                        $.each(response.data, function(index, category) {
                            rows += `
                          <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form id="update-form-${category.id}" action="/manager/other/categories/${category.id}" method="POST" class="inline-flex">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <input id="name" class="rounded block w-full p-2 dark:bg-dark-eval-1" type="text" name="name" value="${category.name}" required autofocus>
                            </td>   

                              <td class="px-6 py-4 whitespace-nowrap">
                                    <x-button type="submit" form="update-form-${category.id}" variant="warning" class="justify-center max-w-sm gap-2">
                                        <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                    </x-button>
                                    </form>

                                    <form method="POST" action="/manager/other/categories/${category.id}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <x-button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">
                                            <x-heroicon-o-trash class="w-3 h-3" aria-hidden="true" />
                                        </x-button>
                                    </form>
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
                }
            });
        }

        fetchcategories(page);

        $('#nextPage').on('click', function() {
            if (page < lastPage) {
                page++;
                fetchcategories(page, searchQuery);
            }
        });

        $('#prevPage').on('click', function() {
            if (page > 1) {
                page--;
                fetchcategories(page, searchQuery);
            }
        });

        $('#search').on('keyup', function() {
            searchQuery = $(this).val();
            page = 1;
            fetchcategories(page, searchQuery);
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
            fetchcategories(page, searchQuery);
        });

        $(window).on('resize', function() {
            generatePaginationButtons(page, lastPage);
        });
    });
</script>
