<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
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
            success: function (response) {
                let rows = '';
                if (response.data.length === 0) {
                    rows = `<tr><td colspan="8" class="py-3 px-6 text-center">Data tidak ditemukan</td></tr>`;
                } else {
                    $.each(response.data, function (index, item) {
                        let warehouseInfo = item.item_warehouse.find(w => w.id ==
                                warehouseId);
                        let stockQty = warehouseInfo ? warehouseInfo.pivot.stock : 0;

                        rows += `
                        <tr class="border dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-slate-900">
                          <td class="px-4 py-4 whitespace-nowrap">${item.name}</td>
                          <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">${item.code}</td>
                          <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">${item.unit}</td>
                          <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">${stockQty}</td>
                          <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">${item.category.name}</td>
                          <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">${formatRupiah(item.purchase_price)}</td>
                          <td class="px-4 py-4 whitespace-nowrap">
                              <button 
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                                onclick="openMutationModal(${item.id}, ${stockQty}, ${item.purchase_price})">
                                Mutasi
                            </button>
                          </td>
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

    $('#nextPage').on('click', function () {
        if (page < lastPage) {
            page++;
            fetchItems(page, searchQuery);
        }
    });

    $('#prevPage').on('click', function () {
        if (page > 1) {
            page--;
            fetchItems(page, searchQuery);
        }
    });

    $('#search').on('keyup', function () {
        searchQuery = $(this).val();
        page = 1;
        fetchItems(page, searchQuery);
    });

    $('#warehouseId').on('change', function () {
        warehouseId = $(this).val();
        page = 1;
        fetchItems(page);
    });

    window.openMutationModal = function (itemId, stockNow, purchasePrice) {
        $('#mutationModal').removeClass('hidden').addClass('flex');
        $('#itemId').val(itemId);
        $('#stock_now').val(stockNow);
        $('#purchasePrice').val(purchasePrice);

        $('#toWarehouse').empty().append('<option value="">Pilih Gudang Tujuan</option>');
        loadWarehouses();

        $('#toWarehouse').select2({
            dropdownParent: $('#mutationModal'),
            width: '100%'
        });
    }


    window.closeMutationModal = function () {
        $('#mutationModal').removeClass('flex').addClass('hidden');
        $('#mutationForm')[0].reset();
        $('#toWarehouse').val(null).trigger('change');
    }

    function loadWarehouses() {
        $.ajax({
            url: '/mutation/get-warehouse',
            method: 'GET',
            success: function (data) {
                let currentFromWarehouse = $('#fromWarehouse').val();

                data.forEach(function (warehouse) {
                    if (warehouse.id != currentFromWarehouse) {
                        $('#toWarehouse').append(`<option value="${warehouse.id}">${warehouse.name}</option>`);
                    }
                });
            }
        });
    }

    $('#mutationForm').on('submit', function (e) {
        e.preventDefault();

        const data = {
            item_id: $('#itemId').val(),
            from_warehouse_id: $('#fromWarehouse').val(),
            to_warehouse_id: $('#toWarehouse').val(),
            price_per_item: $('#purchasePrice').val(),
            qty: $('#quantity').val(),
            note: $('#note').val()
        };

        console.log(data);

        if (!data.to_warehouse_id) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gudang tujuan harus dipilih!'
            });
            return;
        }

        if (parseInt(data.qty) > parseInt($('#stock_now').val())) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Jumlah mutasi tidak boleh melebihi stok saat ini!'
            });
            return;
        }
        
        $.ajax({
            url: '/mutation/store',
            method: 'POST',
            data: data,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Mutasi berhasil dilakukan!'
                });
                closeMutationModal();
                fetchItems(page, searchQuery);
            },
            error: function (xhr) {
                alert('Gagal melakukan mutasi!');
                console.log(xhr.responseJSON || xhr.responseText);
            }
        });
    });
});
</script>