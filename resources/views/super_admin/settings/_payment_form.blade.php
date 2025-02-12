<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.paymob_settings') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="paymob_api_key">{{ __('messages.paymob_api_key') }}</label>
                        <input name="paymob_api_key" type="text" class="form-control" value="{{ get_system_setting('paymob_api_key') }}">
                    </div>
                </div>
            </div>

            <hr>
            <h6>Credit Card</h6>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paymob_credit_card_integration_id">{{ __('messages.paymob_credit_card_integration_id') }}</label>
                        <input name="paymob_credit_card_integration_id" type="text" class="form-control" value="{{ get_system_setting('paymob_credit_card_integration_id') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paymob_credit_card_iframe_id">{{ __('messages.paymob_credit_card_iframe_id') }}</label>
                        <input name="paymob_credit_card_iframe_id" type="text" class="form-control" value="{{ get_system_setting('paymob_credit_card_iframe_id') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_credit_card_fixed_fee">Credit Card Fixed Fee</label>
                        <input name="paymob_credit_card_fixed_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_credit_card_fixed_fee') }}" required>
                        <small class="form-text text-muted">Ex: 1</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_credit_card_percent_fee">Credit Card Percent Fee</label>
                        <input name="paymob_credit_card_percent_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_credit_card_percent_fee') }}" required>
                        <small class="form-text text-muted">Ex: 3.3 (Without %)</small>
                    </div>
                </div> 
            </div> 

            <hr>
            <h6>Valu</h6>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paymob_valu_integration_id">{{ __('messages.paymob_valu_integration_id') }}</label>
                        <input name="paymob_valu_integration_id" type="text" class="form-control" value="{{ get_system_setting('paymob_valu_integration_id') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paymob_valu_iframe_id">{{ __('messages.paymob_valu_iframe_id') }}</label>
                        <input name="paymob_valu_iframe_id" type="text" class="form-control" value="{{ get_system_setting('paymob_valu_iframe_id') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_valu_fixed_fee">ValU Fixed Fee</label>
                        <input name="paymob_valu_fixed_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_valu_fixed_fee') }}" required>
                        <small class="form-text text-muted">Ex: 1</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_valu_percent_fee">ValU Percent Fee</label>
                        <input name="paymob_valu_percent_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_valu_percent_fee') }}" required>
                        <small class="form-text text-muted">Ex: 3.3 (Without %)</small>
                    </div>
                </div> 
            </div> 

            <hr>
            <h6>Premium Card</h6>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paymob_premium_card_integration_id">{{ __('messages.paymob_premium_card_integration_id') }}</label>
                        <input name="paymob_premium_card_integration_id" type="text" class="form-control" value="{{ get_system_setting('paymob_premium_card_integration_id') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paymob_premium_card_iframe_id">{{ __('messages.paymob_premium_card_iframe_id') }}</label>
                        <input name="paymob_premium_card_iframe_id" type="text" class="form-control" value="{{ get_system_setting('paymob_premium_card_iframe_id') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_premium_card_fixed_fee">Premium Card Fixed Fee</label>
                        <input name="paymob_premium_card_fixed_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_premium_card_fixed_fee') }}" required>
                        <small class="form-text text-muted">Ex: 1</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_premium_card_percent_fee">Premium Card Percent Fee</label>
                        <input name="paymob_premium_card_percent_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_premium_card_percent_fee') }}" required>
                        <small class="form-text text-muted">Ex: 3.3 (Without %)</small>
                    </div>
                </div> 
            </div> 

            <hr>
            <h6>Bank Installment</h6>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paymob_bank_installment_integration_id">{{ __('messages.paymob_bank_installment_integration_id') }}</label>
                        <input name="paymob_bank_installment_integration_id" type="text" class="form-control" value="{{ get_system_setting('paymob_bank_installment_integration_id') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paymob_bank_installment_iframe_id">{{ __('messages.paymob_bank_installment_iframe_id') }}</label>
                        <input name="paymob_bank_installment_iframe_id" type="text" class="form-control" value="{{ get_system_setting('paymob_bank_installment_iframe_id') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_bank_installment_fixed_fee">Bank Installment Fixed Fee</label>
                        <input name="paymob_bank_installment_fixed_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_bank_installment_fixed_fee') }}" required>
                        <small class="form-text text-muted">Ex: 1</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_bank_installment_percent_fee">Bank Installment Percent Fee</label>
                        <input name="paymob_bank_installment_percent_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_bank_installment_percent_fee') }}" required>
                        <small class="form-text text-muted">Ex: 3.3 (Without %)</small>
                    </div>
                </div> 
            </div> 

            <hr>
            <h6>Mobile Wallet</h6>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="paymob_mobile_wallet_integration_id">{{ __('messages.paymob_mobile_wallet_integration_id') }}</label>
                        <input name="paymob_mobile_wallet_integration_id" type="text" class="form-control" value="{{ get_system_setting('paymob_mobile_wallet_integration_id') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_mobile_wallet_fixed_fee">Mobile Wallet Fixed Fee</label>
                        <input name="paymob_mobile_wallet_fixed_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_mobile_wallet_fixed_fee') }}" required>
                        <small class="form-text text-muted">Ex: 1</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="paymob_mobile_wallet_percent_fee">Mobile Wallet Percent Fee</label>
                        <input name="paymob_mobile_wallet_percent_fee" type="text" class="form-control" value="{{ get_system_setting('paymob_mobile_wallet_percent_fee') }}" required>
                        <small class="form-text text-muted">Ex: 3.3 (Without %)</small>
                    </div>
                </div> 
            </div> 
            
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="paymob_active">{{ __('messages.active') }}</label>
                        <select name="paymob_active" class="form-control">
                            <option value="0" {{ get_system_setting('paymob_active') == false ? 'selected' : '' }}>{{ __('messages.disabled') }}</option>
                            <option value="1" {{ get_system_setting('paymob_active') == true  ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Transaction processed and response callback url</label>
                        <input type="text" class="form-control" value="{{ route('order.payment.paymob.webhook') }}" disabled>
                        <small>Make sure to put this url as your transaction processed and response callback url address.</small>
                    </div>
                </div>
            </div>

            <div class="form-group text-center mt-5">
                <button class="btn btn-primary save_form_button">{{ __('messages.save_settings') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.paypal_settings') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group required">
                        <label for="paypal_username">{{ __('messages.username') }}</label>
                        <input name="paypal_username" type="text" class="form-control" placeholder="{{ __('messages.username') }}" value="{{ get_system_setting('paypal_username') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group required">
                        <label for="paypal_password">{{ __('messages.password') }}</label>
                        <input name="paypal_password" type="text" class="form-control" placeholder="{{ __('messages.password') }}" value="{{ get_system_setting('paypal_password') }}">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group required">
                        <label for="paypal_signature">{{ __('messages.signature') }}</label>
                        <input name="paypal_signature" type="text" class="form-control" placeholder="{{ __('messages.enter_signature') }}" value="{{ get_system_setting('paypal_signature') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paypal_test_mode">{{ __('messages.test_mode') }}</label>
                        <select name="paypal_test_mode" class="form-control">
                            <option value="0" {{ get_system_setting('paypal_test_mode') == false ? 'selected' : '' }}>{{ __('messages.false') }}</option>
                            <option value="1" {{ get_system_setting('paypal_test_mode') == true  ? 'selected' : '' }}>{{ __('messages.true') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="paypal_active">{{ __('messages.active') }}</label>
                        <select name="paypal_active" class="form-control">
                            <option value="0" {{ get_system_setting('paypal_active') == false ? 'selected' : '' }}>{{ __('messages.disabled') }}</option>
                            <option value="1" {{ get_system_setting('paypal_active') == true  ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="paypal_fixed_fee">Fixed Fee</label>
                        <input name="paypal_fixed_fee" type="text" class="form-control" value="{{ get_system_setting('paypal_fixed_fee') }}" required>
                        <small class="form-text text-muted">Ex: 1</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="paypal_percent_fee">Percent Fee</label>
                        <input name="paypal_percent_fee" type="text" class="form-control" value="{{ get_system_setting('paypal_percent_fee') }}" required>
                        <small class="form-text text-muted">Ex: 3.3 (Without %)</small>
                    </div>
                </div> 
            </div> 

            <div class="form-group text-center mt-5">
                <button class="btn btn-primary save_form_button">{{ __('messages.save_settings') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.stripe_settings') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group required">
                        <label for="stripe_public_key">{{ __('messages.publishable_key') }}</label>
                        <input name="stripe_public_key" type="text" class="form-control" placeholder="{{ __('messages.publishable_key') }}" value="{{ get_system_setting('stripe_public_key') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group required">
                        <label for="stripe_secret_key">{{ __('messages.secret_key') }}</label>
                        <input name="stripe_secret_key" type="text" class="form-control" placeholder="{{ __('messages.secret_key') }}" value="{{ get_system_setting('stripe_secret_key') }}">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="stripe_test_mode">{{ __('messages.test_mode') }}</label>
                        <select name="stripe_test_mode" class="form-control">
                            <option value="0" {{ get_system_setting('stripe_test_mode') == false ? 'selected' : '' }}>{{ __('messages.false') }}</option>
                            <option value="1" {{ get_system_setting('stripe_test_mode') == true  ? 'selected' : '' }}>{{ __('messages.true') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="stripe_active">{{ __('messages.active') }}</label>
                        <select name="stripe_active" class="form-control">
                            <option value="0" {{ get_system_setting('stripe_active') == false ? 'selected' : '' }}>{{ __('messages.disabled') }}</option>
                            <option value="1" {{ get_system_setting('stripe_active') == true  ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="stripe_fixed_fee">Fixed Fee</label>
                        <input name="stripe_fixed_fee" type="text" class="form-control" value="{{ get_system_setting('stripe_fixed_fee') }}" required>
                        <small class="form-text text-muted">Ex: 1</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="stripe_percent_fee">Percent Fee</label>
                        <input name="stripe_percent_fee" type="text" class="form-control" value="{{ get_system_setting('stripe_percent_fee') }}" required>
                        <small class="form-text text-muted">Ex: 3.3 (Without %)</small>
                    </div>
                </div> 
            </div> 

            <div class="form-group text-center mt-5">
                <button class="btn btn-primary save_form_button">{{ __('messages.save_settings') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.razorpay_settings') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group required">
                        <label for="razorpay_id">{{ __('messages.razorpay_id') }}</label>
                        <input name="razorpay_id" type="text" class="form-control" placeholder="{{ __('messages.razorpay_id') }}" value="{{ get_system_setting('razorpay_id') }}">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group required">
                        <label for="razorpay_secret_key">{{ __('messages.razorpay_secret_key') }}</label>
                        <input name="razorpay_secret_key" type="text" class="form-control" placeholder="{{ __('messages.razorpay_secret_key') }}" value="{{ get_system_setting('razorpay_secret_key') }}">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="razorpay_test_mode">{{ __('messages.test_mode') }}</label>
                        <select name="razorpay_test_mode" class="form-control">
                            <option value="0" {{ get_system_setting('razorpay_test_mode') == false ? 'selected' : '' }}>{{ __('messages.false') }}</option>
                            <option value="1" {{ get_system_setting('razorpay_test_mode') == true  ? 'selected' : '' }}>{{ __('messages.true') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="razorpay_active">{{ __('messages.active') }}</label>
                        <select name="razorpay_active" class="form-control">
                            <option value="0" {{ get_system_setting('razorpay_active') == false ? 'selected' : '' }}>{{ __('messages.disabled') }}</option>
                            <option value="1" {{ get_system_setting('razorpay_active') == true  ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="razorpay_fixed_fee">Fixed Fee</label>
                        <input name="razorpay_fixed_fee" type="text" class="form-control" value="{{ get_system_setting('razorpay_fixed_fee') }}" required>
                        <small class="form-text text-muted">Ex: 1</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="razorpay_percent_fee">Percent Fee</label>
                        <input name="razorpay_percent_fee" type="text" class="form-control" value="{{ get_system_setting('razorpay_percent_fee') }}" required>
                        <small class="form-text text-muted">Ex: 3.3 (Without %)</small>
                    </div>
                </div> 
            </div> 

            <div class="form-group text-center mt-5">
                <button class="btn btn-primary save_form_button">{{ __('messages.save_settings') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.mollie_settings') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group required">
                        <label for="mollie_api_key">{{ __('messages.mollie_api_key') }}</label>
                        <input name="mollie_api_key" type="text" class="form-control" placeholder="{{ __('messages.mollie_api_key') }}" value="{{ get_system_setting('mollie_api_key') }}">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="mollie_test_mode">{{ __('messages.test_mode') }}</label>
                        <select name="mollie_test_mode" class="form-control">
                            <option value="0" {{ get_system_setting('mollie_test_mode') == false ? 'selected' : '' }}>{{ __('messages.false') }}</option>
                            <option value="1" {{ get_system_setting('mollie_test_mode') == true  ? 'selected' : '' }}>{{ __('messages.true') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="mollie_active">{{ __('messages.active') }}</label>
                        <select name="mollie_active" class="form-control">
                            <option value="0" {{ get_system_setting('mollie_active') == false ? 'selected' : '' }}>{{ __('messages.disabled') }}</option>
                            <option value="1" {{ get_system_setting('mollie_active') == true  ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="mollie_fixed_fee">Fixed Fee</label>
                        <input name="mollie_fixed_fee" type="text" class="form-control" value="{{ get_system_setting('mollie_fixed_fee') }}" required>
                        <small class="form-text text-muted">Ex: 1</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="mollie_percent_fee">Percent Fee</label>
                        <input name="mollie_percent_fee" type="text" class="form-control" value="{{ get_system_setting('mollie_percent_fee') }}" required>
                        <small class="form-text text-muted">Ex: 3.3 (Without %)</small>
                    </div>
                </div> 
            </div> 

            <div class="form-group text-center mt-5">
                <button class="btn btn-primary save_form_button">{{ __('messages.save_settings') }}</button>
            </div>
        </div>
    </div>
</div>
