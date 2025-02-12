<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.application_settings') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>{{ __('messages.logo') }}</label><br>
                        <input id="logo" name="logo" class="d-none" type="file" onchange="changePreview(this);">
                        <label for="logo">
                            <div class="media align-items-center">
                                <div class="mr-3">
                                    <div class="avatar avatar-xl">
                                        <img id="file-prev" src="{{ asset(get_system_setting('application_logo')) }}" class="avatar-img rounded">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <a class="btn btn-sm btn-light choose-button">{{ __('messages.choose_photo') }}</a>
                                </div>
                            </div>
                        </label> 
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>{{ __('messages.favicon') }}</label><br>
                        <input id="favicon" name="favicon" class="d-none" type="file" onchange="changePreview(this);">
                        <label for="favicon">
                            <div class="media align-items-center">
                                <div class="mr-3">
                                    <div class="avatar avatar-xl">
                                        <img id="file-prev" src="{{ asset(get_system_setting('application_favicon')) }}" class="avatar-img rounded">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <a class="btn btn-sm btn-light choose-button">{{ __('messages.choose_photo') }}</a>
                                </div>
                            </div>
                        </label> 
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group required">
                        <label for="application_name">{{ __('messages.application_name') }}</label>
                        <input name="application_name" type="text" class="form-control" placeholder="{{ __('messages.application_name') }}" value="{{ get_system_setting('application_name') }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group required">
                        <label for="meta_description">{{ __('messages.meta_description') }}</label>
                        <input name="meta_description" type="text" class="form-control" placeholder="{{ __('messages.meta_description') }}" value="{{ get_system_setting('meta_description') }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group required">
                        <label for="meta_keywords">{{ __('messages.meta_keywords') }}</label>
                        <input name="meta_keywords" type="text" class="form-control" placeholder="{{ __('messages.meta_keywords') }}" value="{{ get_system_setting('meta_keywords') }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group required">
                        <label for="application_currency">{{ __('messages.currency') }}</label> 
                        <select name="application_currency" data-toggle="select" class="form-control select2-hidden-accessible" data-select2-id="application_currency" required>
                            <option disabled selected>{{ __('messages.select_currency') }}</option>
                            @php $appCurrency = get_system_setting('application_currency') @endphp
                            @foreach(get_currencies_select2_array() as $option)
                                <option value="{{ $option['code'] }}" {{ $appCurrency == $option['code'] ? 'selected=""' : '' }} >{{ $option['text'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="google_recapthca_key">{{ __('messages.google_recapthca_key') }}</label> 
                        <input name="google_recapthca_key" type="text" class="form-control" placeholder="{{ __('messages.google_recapthca_key') }}" value="{{ env('GOOGLE_CAPTCHA_SITE_KEY') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="google_recapthca_secret_key">{{ __('messages.google_recapthca_secret_key') }}</label> 
                        <input name="google_recapthca_secret_key" type="text" class="form-control" placeholder="{{ __('messages.google_recapthca_secret_key') }}" value="{{ env('GOOGLE_CAPTCHA_PRIVATE_KEY') }}">
                    </div>
                </div>
            </div>

            <hr> 

            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-4">
                        <p class="h5 mb-0">
                            <strong class="headings-color">{{ __('messages.expiring_subscription_due_reminder') }}</strong>
                        </p>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group required">
                        <label for="expiring_subscription_due_mail_subject">{{ __('messages.subject') }}</label>
                        <input name="expiring_subscription_due_mail_subject" type="text" class="form-control" placeholder="{{ __('messages.subject') }}" value="{{ get_system_setting('expiring_subscription_due_mail_subject') }}" required>
                    </div>
                </div> 

                <div class="col-12">
                    <div class="form-group required">
                        <label>{{ __('messages.content') }}</label>
                        <div class="quill h-250px" data-toggle="quill" data-quill-placeholder="{{ __('messages.content') }}" data-quill-modules-toolbar='[["bold", "italic", "underline"], ["link", "blockquote"], [{"list": "ordered"}, {"list": "bullet"}]]'>
                            {!! get_system_setting('expiring_subscription_due_mail_content') !!}
                        </div>
                        <textarea name="expiring_subscription_due_mail_content" class="d-none" required>{!! get_system_setting('expiring_subscription_due_mail_content') !!}</textarea>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="expiring_subscription_due_before_x_days">{{ __('messages.expiring_subscription_due_before_x_days') }}</label>
                        <input name="expiring_subscription_due_before_x_days" type="number" min="0" class="form-control" value="{{ get_system_setting('expiring_subscription_due_before_x_days') }}">
                    </div>
                </div>
            </div>

            <hr> 

            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-4">
                        <p class="h5 mb-0">
                            <strong class="headings-color">{{ __('messages.expiring_subscription_overdue_reminder') }}</strong>
                        </p>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group required">
                        <label for="expiring_subscription_overdue_mail_subject">{{ __('messages.subject') }}</label>
                        <input name="expiring_subscription_overdue_mail_subject" type="text" class="form-control" placeholder="{{ __('messages.subject') }}" value="{{ get_system_setting('expiring_subscription_overdue_mail_subject') }}" required>
                    </div>
                </div> 

                <div class="col-12">
                    <div class="form-group required">
                        <label>{{ __('messages.content') }}</label>
                        <div class="quill h-250px" data-toggle="quill" data-quill-placeholder="{{ __('messages.content') }}" data-quill-modules-toolbar='[["bold", "italic", "underline"], ["link", "blockquote"], [{"list": "ordered"}, {"list": "bullet"}]]'>
                            {!! get_system_setting('expiring_subscription_overdue_mail_content') !!}
                        </div>
                        <textarea name="expiring_subscription_overdue_mail_content" class="d-none" required>{!! get_system_setting('expiring_subscription_overdue_mail_content') !!}</textarea>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="expiring_subscription_overdue_after_x_days">{{ __('messages.expiring_subscription_overdue_after_x_days') }}</label>
                        <input name="expiring_subscription_overdue_after_x_days" type="number" min="0" class="form-control" value="{{ get_system_setting('expiring_subscription_overdue_after_x_days') }}">
                    </div>
                </div>
            </div>

            <div class="form-group text-center mt-5">
                <button class="btn btn-primary save_form_button">{{ __('messages.save_settings') }}</button>
            </div>
        </div>
    </div>
</div>

