<script>
  $(document).ready(function() {
      let page = 1;
      let lastPage = 1;
      let searchQuery = '';

      function fetchdistributors(page, searchQuery = '') {

          $.ajax({
              url: '/distributors-data?page=' + page + '&search=' + searchQuery,
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
                      $.each(response.data, function(index, distributor) {
                          rows += `
                        <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                              <td class="px-6 py-4 whitespace-nowrap">${distributor.name}</td>
                              <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${distributor.address}</td>
                              <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${distributor.phone}</td>
                              <td class="px-6 py-4 whitespace-nowrap">
                                  <x-button target="" href="/manager/distributors/${distributor.id}/edit" variant="warning" class="justify-center max-w-sm gap-2">
                                      <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                  </x-button>
                                  <!-- Destroy form -->
                                      <form action="/manager/distributors/${distributor.id}" method="POST" class="inline-block">
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
                                      <p><strong>Alamat:</strong> ${distributor.address}</p>
                                      <p><strong>Nomor telepon:</strong> ${distributor.phone}</p>
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

      fetchdistributors(page);

      $('#nextPage').on('click', function() {
          if (page < lastPage) {
              page++;
              fetchdistributors(page, searchQuery);
          }
      });

      $('#prevPage').on('click', function() {
          if (page > 1) {
              page--;
              fetchdistributors(page, searchQuery);
          }
      });

      $('#search').on('keyup', function() {
          searchQuery = $(this).val();
          page = 1;
          fetchdistributors(page, searchQuery);
      });
  });
</script>
