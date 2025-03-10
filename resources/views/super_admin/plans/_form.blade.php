<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.plan_information') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="name">{{ __('messages.name') }}</label>
                        <input name="name" type="text" class="form-control" placeholder="{{ __('messages.name') }}" value="{{ $plan->name }}" required>
                    </div>
                </div> 
                <div class="col">
                    <div class="form-group">
                        <label for="description">{{ __('messages.description') }}</label>
                        <input name="description" type="text" class="form-control" placeholder="{{ __('messages.description') }}" value="{{ $plan->description }}">
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="price">{{ __('messages.price') }}</label>
                        <input name="price" type="text" class="form-control price_input" placeholder="{{ __('messages.price') }}" autocomplete="off" value="{{ $plan->price ?? 0 }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_price_helper') }}</small>
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="invoice_interval">{{ __('messages.invoice_interval') }}</label>
                        <select name="invoice_interval"  class="form-control" required>
                            <option value="month" {{ $plan->invoice_interval === 'month' ? 'selected=""' : ''}}>{{ __('messages.month') }}</option>
                            <option value="year" {{ $plan->invoice_interval === 'year' ? 'selected=""' : ''}}>{{ __('messages.year') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="trial_period">{{ __('messages.trial_period') }}</label>
                        <input name="trial_period" type="number" class="form-control" placeholder="{{ __('messages.trial_period') }}" value="{{ $plan->trial_period }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_trial_period_helper') }}</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="order">{{ __('messages.order') }}</label>
                        <input name="order" type="number" class="form-control" placeholder="{{ __('messages.order') }}" value="{{ $plan->order }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.plan_feature_information') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="features[customers]">{{ __('messages.feature_customers') }}</label>
                        <input name="features[customers]" type="text" class="form-control" placeholder="{{ __('messages.feature_customers') }}" value="{{ isset($plan->getFeatureBySlug('customers')->value) ? $plan->getFeatureBySlug('customers')->value : '' }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_feature_unlimited_helper') }}</small>
                    </div>
                </div> 
                <div class="col">
                    <div class="form-group required">
                        <label for="features[products]">{{ __('messages.feature_products') }}</label>
                        <input name="features[products]" type="text" class="form-control" placeholder="{{ __('messages.feature_products') }}" value="{{ isset($plan->getFeatureBySlug('products')->value) ? $plan->getFeatureBySlug('products')->value : '' }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_feature_unlimited_helper') }}</small>
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="features[estimates_per_month]">{{ __('messages.feature_estimates_per_month') }}</label>
                        <input name="features[estimates_per_month]" type="text" class="form-control" placeholder="{{ __('messages.feature_estimates_per_month') }}" value="{{ isset($plan->getFeatureBySlug('estimates_per_month')->value) ? $plan->getFeatureBySlug('estimates_per_month')->value : '' }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_feature_unlimited_helper') }}</small>
                    </div>
                </div> 
                <div class="col">
                    <div class="form-group required">
                        <label for="features[invoices_per_month]">{{ __('messages.feature_invoices_per_month') }}</label>
                        <input name="features[invoices_per_month]" type="text" class="form-control" placeholder="{{ __('messages.feature_invoices_per_month') }}" value="{{ isset($plan->getFeatureBySlug('invoices_per_month')->value) ? $plan->getFeatureBySlug('invoices_per_month')->value : '' }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_feature_unlimited_helper') }}</small>
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="features[view_reports]">{{ __('messages.feature_can_view_reports') }}</label>
                        <select name="features[view_reports]"  class="form-control" required>
                            <option value="1" {{ $plan->id !== null ? $plan->getFeatureBySlug('view_reports')->value === '1' ? 'selected=""' : '' : ''}}>{{ __('messages.yes') }}</option>
                            <option value="0" {{ $plan->id !== null ? $plan->getFeatureBySlug('view_reports')->value === '0' ? 'selected=""' : '' : ''}}>{{ __('messages.no') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="features[advertisement_on_mails]">{{ __('messages.feature_advertisement_on_mails') }}</label>
                        <select name="features[advertisement_on_mails]"  class="form-control" required>
                            <option value="1" {{ $plan->id !== null ? $plan->getFeatureBySlug('advertisement_on_mails')->value === '1' ? 'selected=""' : '' : ''}}>{{ __('messages.yes') }}</option>
                            <option value="0" {{ $plan->id !== null ? $plan->getFeatureBySlug('advertisement_on_mails')->value === '0' ? 'selected=""' : '' : ''}}>{{ __('messages.no') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="features[withdraw_limit]">{{ __('messages.feature_withdraw_limit') }}</label>
                        <input name="features[withdraw_limit]" type="number" class="form-control" value="{{ isset($plan->getFeatureBySlug('withdraw_limit')->value) ? $plan->getFeatureBySlug('withdraw_limit')->value : '' }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_feature_disable_helper') }}</small>
                    </div>
                </div> 
                
            </div> 

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="features[withdraw_fixed_fee]">{{ __('messages.feature_withdraw_fixed_fee') }}</label>
                        <input name="features[withdraw_fixed_fee]" type="number" class="form-control" value="{{ isset($plan->getFeatureBySlug('withdraw_fixed_fee')->value) ? $plan->getFeatureBySlug('withdraw_fixed_fee')->value : '' }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_feature_disable_helper') }}</small>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="features[withdraw_percent_fee]">{{ __('messages.feature_withdraw_percent_fee') }}</label>
                        <input name="features[withdraw_percent_fee]" type="number" class="form-control" value="{{ isset($plan->getFeatureBySlug('withdraw_percent_fee')->value) ? $plan->getFeatureBySlug('withdraw_percent_fee')->value : '' }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_feature_disable_helper') }}</small>
                    </div>
                </div> 
            </div> 

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="features[online_payment_fixed_fee]">{{ __('messages.feature_online_payment_fixed_fee') }}</label>
                        <input name="features[online_payment_fixed_fee]" type="number" class="form-control" value="{{ isset($plan->getFeatureBySlug('online_payment_fixed_fee')->value) ? $plan->getFeatureBySlug('online_payment_fixed_fee')->value : '' }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_feature_disable_helper') }}</small>
                    </div>
                </div> 
                <div class="col">
                    <div class="form-group required">
                        <label for="features[online_payment_percent_fee]">{{ __('messages.feature_online_payment_percent_fee') }}</label>
                        <input name="features[online_payment_percent_fee]" type="number" class="form-control" value="{{ isset($plan->getFeatureBySlug('online_payment_percent_fee')->value) ? $plan->getFeatureBySlug('online_payment_percent_fee')->value : '' }}" required>
                        <small class="form-text text-muted">{{ __('messages.plan_feature_disable_helper') }}</small>
                    </div>
                </div> 
            </div> 
            
            <div class="form-group text-center mt-5">
                <button class="btn btn-primary save_form_button">{{ __('messages.save_plan') }}</button>
            </div>
        </div>
    </div>
</div>