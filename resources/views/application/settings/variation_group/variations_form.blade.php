
@section("scripts")
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>


    @section("scripts")
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    tags: true,
                    tokenSeparators: [',']
                });

                // Add new row
                $('#add-row-btn').click(function() {
                    var newRow = $('<tr>' +
                        '<td>'+
                        '<i class="fas fa-grip-vertical sortable-handle"></i>'+
                        '</td>'+
                        '<td>{{Form::select("variations[]",\App\Models\Variations::getSelect2Array($currentCompany->id),null,["class"=>"form-control"])}}</td>' +
                        '<td><button class="btn btn-danger delete-row-btn">Delete</button></td>' +
                        '</tr>');

                    $('.table-body').append(newRow);
                    $('.select2').select2({
                        tags: true,
                        tokenSeparators: [',']
                    });
                });

                // Initialize sortable
                var sortable = new Sortable(document.querySelector('.table-body'), {
                    handle: '.sortable-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                });

                // Delete row
                $(document).on('click', '.delete-row-btn', function() {
                    $(this).closest('tr').remove();
                });
            });
        </script>
    @endsection

@endsection

<div class="row">
    <div class="col-12">
        <table class="table table-xl mb-0 thead-border-top-0 table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.name') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody class="table-body">

            @foreach($variation_group->GroupVariations()->get() as $attribute)
                <tr>
                    <td>
                        <i class="fas fa-grip-vertical sortable-handle"></i>
                    </td>
                    <td>
                        {{Form::select("variations[]",\App\Models\Variations::getSelect2Array($currentCompany->id),$attribute->variations_id,["class"=>"form-control"])}}
                    </td>
                    <td>

                        <button class="btn btn-danger delete-row-btn">{{__("messages.delete")}}</button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        <button id="add-row-btn" type="button" class="btn btn-primary m-2">{{__("messages.add_row")}}</button>
    </div>
</div>
