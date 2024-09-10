<script>
    $(document).ready(function() {
        var selectedCategoryId = null;

        $('#selectInput').on('input', function() {
            var query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: "{{ route('categories.search') }}",
                    type: 'GET',
                    data: {
                        name: query
                    },
                    success: function(data) {
                        $('#selectOptions').empty();
                        if (data.length > 0) {
                            data.forEach(function(category) {
                                $('#selectOptions').append('<li data-id="' +
                                    category.id + '">' + category.name + '</li>'
                                );
                            });
                            $('#selectOptions').show();
                        } else {
                            $('#selectOptions').append(
                                "<div class='block w-full px-4 py-4 bg-white border border-gray-300 rounded-md shadow-sm'> Tidak ada hasil. Klik " +
                                "<button type='button' class='submitButton px-2 py-2 bg-blue-500 text-white font-semibold rounded-sms shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75'>" +
                                query + "</button>" +
                                " untuk menambahkan" +
                                "</div>"
                            );
                            $('#selectOptions').show();
                        }
                    }
                });
            } else {
                $('#selectOptions').hide();
            }
        });

        $(document).on('click', '#selectOptions li', function() {
            var selectedText = $(this).text();
            selectedCategoryId = $(this).data('id');
            $('#selectInput').val(selectedText);
            $('#category_id').val(selectedCategoryId);
            $('#selectOptions').hide();
        });

        $(document).on('click', '.submitButton', function() {
            var newCategory = $('#selectInput').val();
            if (newCategory.length > 0) {
                $.ajax({
                    url: "{{ route('categories.storeinput') }}",
                    type: 'POST',
                    data: {
                        name: newCategory,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#selectInput').val(data.name);
                        $('#category_id').val(data.id);
                        alert('Kategori "' + data.name + '" berhasil ditambahkan!');
                        $('#selectOptions').hide();
                    }
                });
            } else {
                alert('Input tidak boleh kosong!');
            }
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.custom-select').length) {
                $('#selectOptions').hide();
            }
        });
    });
</script>
