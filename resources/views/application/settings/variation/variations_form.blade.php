<!-- resources/views/application/settings/variation/variations_form.blade.php -->

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

                @foreach ($variation->variationAttributes as $attribute)
                    <tr>
                        <td class="sortable-handle">
                            <i class="fas fa-grip-vertical"></i>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="name[]"
                                placeholder="{{ __('messages.name') }}" value="{{ $attribute->name }}" required>
                        </td>
                        <td>
                            <button type="button"
                                class="btn btn-danger delete-row-btn">{{ __('messages.delete') }}</button>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <button id="add-row-btn" type="button" class="btn btn-primary m-2">{{ __('messages.add_row') }}</button>
    </div>
</div>
