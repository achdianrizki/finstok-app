<script>
  $(document).ready(function() {
      let page = 1;
      let lastPage = 1;
      let searchQuery = '';
      let warehouseId = $('#warehouseId').val();

      function formatRupiah(number) {
          return new Intl.NumberFormat('id-ID', {
              minimumFractionDigits: 2,
              maximumFractionDigits: 2
          }).format(number);
      }

      function fetchItems(page, searchQuery = '') {
          $.ajax({
              url: `/manager/warehouses/${warehouseId}/items?page=${page}&search=${searchQuery}`,
              method: 'GET',
              success: function(response) {
                  let rows = '';
                  if (response.data.length === 0) {
                      rows =
                          `<tr><td colspan="8" class="py-3 px-6 text-center">Not Found</td></tr>`;
                  } else {
                      $.each(response.data, function(index, item) {
                          let warehouseInfo = item.item_warehouse.find(w => w.id ==
                              warehouseId);
                          let stockQty = warehouseInfo ? warehouseInfo.pivot.stock : 0;
                          let purchasePrice = item.purchase_price;
                          let physicalQty = warehouseInfo && warehouseInfo.pivot
                              .physical !== null ?
                              warehouseInfo.pivot.physical :
                              stockQty;

                          // Hitung Profit & Selisih
                          let profitQty = physicalQty > stockQty ? physicalQty -
                              stockQty : 0;
                          let differenceQty = stockQty - physicalQty;
                          let differenceValue = Math.abs(differenceQty) * purchasePrice;

                          // Warna Selisih
                          let differenceClass = differenceQty === 0 ?
                              'text-green-500 bg-green-100' :
                              (differenceQty < 0 ? 'text-blue-500 bg-blue-100' :
                                  'text-red-500 bg-red-100');

                          rows += `
                          <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                              <td class="px-4 py-4 whitespace-nowrap">${item.name}</td>
                              <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">${item.code}</td>
                              <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">${item.unit}</td>
                              <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">${item.category.name}</td>
                              <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">${formatRupiah(purchasePrice)}</td>
                              <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">${stockQty}</td>
                          </tr>`;
                      });
                  }

                  $('#itemsByWarehouse').html(rows);
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

      $('#warehouseId').on('change', function() {
          warehouseId = $(this).val();
          page = 1;
          fetchItems(page);
      });

      $(document).on('input', '.physical-input', function() {
          let itemId = $(this).data('id');
          let stock = parseInt($(this).data('stock')) || 0;
          let physical = parseInt($(this).val()) || 0;
          let purchasePrice = parseInt($(this).data('price')) || 0;

          let profitQty = physical > stock ? physical - stock : 0;
          let differenceQty = stock - physical;
          let differenceValue = Math.abs(differenceQty) * purchasePrice;

          let differenceField = $(`#difference-${itemId}`);
          let profitField = $(`#profit-${itemId}`);

          let differenceClass = differenceQty === 0 ?
              'text-green-500 bg-green-100' :
              (differenceQty < 0 ? 'text-blue-500 bg-blue-100' : 'text-red-500 bg-red-100');

          profitField.val(profitQty);
          differenceField.val(formatRupiah(differenceValue)).removeClass().addClass(
              `w-full border rounded py-1 px-2 ${differenceClass}`);
      });

      
  });
</script>
