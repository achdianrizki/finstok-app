<script>
  $(document).ready(function() {
      let page = 1;
      let lastPage = 1;
      let searchQuery = '';

      function fetchusers(page, searchQuery = '') {

          $.ajax({
              url: '/users-data?page=' + page + '&search=' + searchQuery,
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
                      $.each(response.data, function(index, user) {
                        let roles = user.roles.map(role => role.name).join(', ');
                        
                          rows += `
                        <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                              <td class="px-6 py-4 whitespace-nowrap">${user.name}</td>
                              <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">${user.email}</td>
                              <td class="px-6 py-4 whitespace-nowrap">${roles}</td>
                              <td class="px-6 py-4 whitespace-nowrap">
                                  <x-button target="" href="/manager/users/${user.id}/edit" variant="warning" class="justify-center max-w-sm gap-2">
                                      <x-heroicon-o-pencil class="w-3 h-3" aria-hidden="true" />
                                  </x-button>
                                  <!-- Destroy form -->
                                      <form action="/manager/users/${user.id}" method="POST" class="inline-block">
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
                                      <p><strong>Alamat:</strong> ${user.name}</p>
                                      <p><strong>Nomor telepon:</strong> ${user.email}</p>
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

      fetchusers(page);

      $('#nextPage').on('click', function() {
          if (page < lastPage) {
              page++;
              fetchusers(page, searchQuery);
          }
      });

      $('#prevPage').on('click', function() {
          if (page > 1) {
              page--;
              fetchusers(page, searchQuery);
          }
      });

      $('#search').on('keyup', function() {
          searchQuery = $(this).val();
          page = 1;
          fetchusers(page, searchQuery);
      });
  });
</script>
