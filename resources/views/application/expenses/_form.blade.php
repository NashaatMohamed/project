<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.expense_information') }}</strong></p>
            <p class="text-muted">{{ __('messages.basic_expense_information') }}</p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="form-group">
                <label>{{ __('messages.receipt') }}</label><br>
                <input type="file" onchange="changePreview(this);" class="d-none" name="receipt" id="receipt">
                <label for="receipt">
                    <div class="media align-items-center">
                        <div class="mr-3">
                            <div class="avatar avatar-xl">
                                <img id="file-prev" src="{{ $expense->receipt ? asset($expense->receipt) : asset('assets/images/account-add-photo.svg') }}" class="avatar-img rounded">
                            </div>
                        </div>
                        <div class="media-body">
                            <a class="btn btn-sm btn-light choose-button">{{ __('messages.choose_file') }}</a>
                        </div>
                    </div>
                </label><br>
                @if($expense->receipt)
                    <a href="{{ asset($expense->receipt) }}" target="_blank" class="btn btn-sm btn-info text-white choose-button">{{ __('messages.download_receipt') }}</a>
                @endif
            </div>
            
            <div class="row">
                <div class="col"> 
                    <div class="form-group select-container required">
                        <label for="expense_category_id">{{ __('messages.category') }}</label> 
                        <select id="expense_category_id" name="expense_category_id" data-toggle="select" class="form-control select2-hidden-accessible select-with-footer" data-select2-id="expense_category_id" data-minimum-results-for-search="-1" required>
                            <option disabled selected>{{ __('messages.select_category') }}</option>
                            @foreach(get_expense_categories_select2_array($currentCompany->id) as $option)
                                <option value="{{ $option['id'] }}" {{ $expense->expense_category_id == $option['id'] ? 'selected=""' : '' }}>{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                        <div class="d-none select-footer">
                            <a href="{{ route('settings.expense_categories.create', ['company_uid' => $currentCompany->uid]) }}" target="_blank" class="font-weight-300">+ {{ __('messages.add_new_expense_category') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col"> 
                    <div class="form-group select-container">
                        <label for="vendor_id">{{ __('messages.vendor') }}</label> 
                        <select name="vendor_id" data-toggle="select" class="form-control select2-hidden-accessible select-with-footer" data-select2-id="vendor_id">
                            <option disabled selected>{{ __('messages.select_vendor') }}</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ $expense->vendor_id == $vendor->id ? 'selected=""' : '' }}>{{ $vendor->display_name }}</option>
                            @endforeach
                        </select>
                        <div class="d-none select-footer">
                            <a href="{{ route('vendors.create', ['company_uid' => $currentCompany->uid]) }}" target="_blank" class="font-weight-300">+ {{ __('messages.add_new_vendor') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col"> 
                    <div class="form-group required">
                        <label for="expense_date">{{ __('messages.expense_date') }}</label>
                        <input name="expense_date" type="text"  class="form-control input" data-toggle="flatpickr" data-flatpickr-default-date="{{ $expense->expense_date ?? now() }}" placeholder="{{ __('messages.expense_date') }}" readonly="readonly" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="amount">{{ __('messages.amount') }}</label>
                        <input name="amount" type="text" class="form-control price_input" placeholder="{{ __('messages.amount') }}" autocomplete="off" value="{{ $expense->amount ?? 0 }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col"> 
                    <div class="form-group"> 
                        <label for="is_recurring">{{ __('messages.is_recurring') }}</label>
                        <select id="is_recurring" name="is_recurring" class="form-control" required>
                            <option value="0" {{ !$expense->is_recurring ? 'selected=""' : '' }}>{{ __('messages.no') }}</option>
                            @for ($i = 1; $i < get_system_setting('recurring_expense_cycle'); $i++)
                                <option value="{{ $i }}" {{ $expense->cycle == $i ? 'selected=""' : '' }}>{{ __('messages.every_x_month', ['month' => $i]) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div id="recurring-cyle" class="form-group" style="{{ !$expense->is_recurring ? 'display: none' : '' }}"> 
                        <label for="cycle">{{ __('messages.cycle') }}</label>
                        <select name="cycle" class="form-control" required>
                            <option value="-1" {{ $expense->cycle == -1 ? 'selected=""' : '' }}>{{ __('messages.infinite') }}</option>
                            @for ($i = 1; $i < get_system_setting('recurring_expense_cycle'); $i++)
                                <option value="{{ $i }}" {{ $expense->cycle == $i ? 'selected=""' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
 
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="notes">{{ __('messages.notes') }}</label>
                        <textarea name="notes" class="form-control" cols="30" rows="3" placeholder="{{ __('messages.notes') }}">{{ $expense->notes }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                @if($expense->getCustomFields()->count() > 0)
                    <div class="col-12">
                        @foreach ($expense->getCustomFields() as $custom_field)
                            @include('layouts._custom_field', ['model' => $expense, 'custom_field' => $custom_field])
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="form-group text-center mt-3">
                <button type="button" class="btn btn-primary form_with_price_input_submit">{{ __('messages.save_expense') }}</button>
            </div>
        </div>
    </div>
</div>

@section('page_body_scripts')
    <script>
        $("#is_recurring").change(function() {
            var isRecurring = $(this).val();
            if (isRecurring === '0') {
                $('#recurring-cyle').hide();   
            } else {
                $('#recurring-cyle').show(); 
            }
        });
    </script>
@endsection