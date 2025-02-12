@section("page_bodscripts")
    <!-- تضمين مكتبة Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- تضمين مكتبة Sortable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <!-- تضمين مكتبة jQuery إذا لم تكن محملة بالفعل -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.select2').select2({
                tags: true,
                tokenSeparators: [',']
            });

            // Add new row
            $('#add-row-btn').click(function () {
                var newRow = $('<tr>' +
                    '<td class="sortable-handle">' +
                    '<i class="fas fa-grip-vertical"></i>' +
                    '</td>' +
                    '<td><input type="text" class="form-control" name="name[]" placeholder="{{ __('messages.name') }}" required></td>' +
                    '<td><button type="button" class="btn btn-danger delete-row-btn">{{ __("messages.delete") }}</button></td>' +
                    '</tr>');

                $('.table-body').append(newRow);
            });

            // Delete row
            $(document).on('click', '.delete-row-btn', function () {
                $(this).closest('tr').remove();
            });

            // Make table rows sortable
            var sortable = new Sortable(document.querySelector('.table-body'), {
                handle: '.sortable-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
            });
        });
    </script>
@endsection
