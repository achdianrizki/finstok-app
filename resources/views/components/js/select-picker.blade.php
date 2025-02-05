<script>
    $(document).ready(function() {
        var modalElement = document.getElementById('crud-modal');
        var modal = new Modal(modalElement);

        $('#selectInput').on('input', function() {
            var query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: "/items-data-sale",
                    type: 'GET',
                    data: {
                        search: query
                    },
                    success: function(data) {
                        $('#selectOptions').empty();
                        if (data.data.length > 0) {
                            data.data.forEach(function(item) {
                                $('#selectOptions').append('<li data-id="' + item.id + '" data-name="' + item.name + '" data-code="' + item.code + '" data-price="' + item.price + '">' + item.name + '</li>');
                            });
                            $('#selectOptions').show();
                        } else {
                            $('#selectOptions').hide();
                        }
                    }
                });
            } else {
                $('#selectOptions').hide();
            }
        });

        $('#selectOptions').on('click', 'li', function() {
            $('#selectInput').val($(this).data('name'));
            $('#item_id').val($(this).data('id'));

            // Open the modal with selected item details
            $('#modal_item_name').val($(this).data('name'));
            $('#modal_item_code').val($(this).data('code'));
            $('#modal_item_price').val($(this).data('price'));
            $('#items_name').text($(this).data('name'));
            $('#items_code').text($(this).data('code'));
            modal.show();
            $('#selectOptions').hide();
        });

        $('#save-item').on('click', function() {
            var name = $('#modal_item_name').val();
            var code = $('#modal_item_code').val();
            var price = $('#modal_item_price').val();
            var qty = $('#modal_qty_sold').val();
            var discount = $('#modal_discount').val();
            var total = qty * price - discount;

            var newRow = `
                <tr>
                    <td>
                        <input type="text" name="items[${code}][name]" value="${name}" class="border-none" readonly>
                    </td>
                    <td>
                        <input type="text" name="items[${code}][code]" value="${code}" class="border-none" readonly>
                    </td>
                    <td>
                        <input type="number" name="items[${code}][qty]" value="${qty}" class="border-none">
                    </td>
                    <td>
                        <input type="text" name="items[${code}][price]" value="${price}" class="border-none">
                    </td>
                    <td>
                        <input type="text" name="items[${code}][discount]" value="${discount}" class="border-none">
                    </td>
                    <td>
                        <input type="text" name="items[${code}][total_price]" value="${total}" class="border-none">
                    </td>
                    <td>
                        <button type="button" class="text-red-600 remove-item">Hapus</button>
                    </td>
                </tr>
            `;
            $('#itemSaleTable').append(newRow);

            modal.hide();
        });

        // Remove item
        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();
        });

        // Close modal
        $('#close-modal').on('click', function() {
            modal.hide();
        });
    });
</script>