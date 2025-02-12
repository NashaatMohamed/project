<div class="row">
    <div class="col"> 
        <div class="form-group required">
            <label for="bank_id">{{ __('messages.bank') }}</label>
            <select name="bank_id" data-toggle="select" class="form-control select2-hidden-accessible" data-select2-id="bank_id" required>
                <option disabled selected>{{ __('messages.select_bank') }}</option>
                @foreach(get_banks_select2_array() as $option)
                    <option value="{{ $option['id'] }}" {{ $withdraw_account->bank_id == $option['id'] ? 'selected=""' : '' }}>{{ $option['text'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group required">
            <label for="iban">{{ __('messages.iban') }}</label>
            <input name="iban" type="text" class="form-control" placeholder="{{ __('messages.iban') }}" value="{{ $withdraw_account->iban }}" required>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group required">
            <label for="full_name">{{ __('messages.full_name') }}</label>
            <input name="full_name" type="text" class="form-control" placeholder="{{ __('messages.full_name') }}" value="{{ $withdraw_account->full_name }}" required>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="additional_info">{{ __('messages.additional_info') }}</label>
            <textarea name="additional_info" class="form-control" rows="5">{{ $withdraw_account->additional_info }}</textarea>
        </div>
    </div>
</div>

