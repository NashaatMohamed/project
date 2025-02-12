@extends('layouts.auth')

@section('title', __('messages.register'))

@section('content')
<h1 class="text-center h6 mb-4">{{ __('messages.register_welcome') }}</h1>

<form id="auth-form" action="{{ route('register') }}" method="POST" novalidate>
    @csrf
    @honeypot
    
    <div class="form-group">
        <label class="text-label" for="first_name">{{ __('messages.first_name') }}:</label>
        <div class="input-group">
            <input id="first_name" name="first_name" type="text"
                class="form-control form-control-prepended @error('first_name') is-invalid @enderror"
                placeholder="{{ __('messages.first_name') }}" value="{{ old('first_name') }}"
                 required>
            @error('first_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label class="text-label" for="last_name">{{ __('messages.last_name') }}:</label>
        <div class="input-group">
            <input id="last_name" name="last_name" type="text"
                class="form-control form-control-prepended @error('last_name') is-invalid @enderror"
                placeholder="{{ __('messages.last_name') }}" value="{{ old('last_name') }}"
                required>
            @error('last_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label class="text-label" for="company_name">{{ __('messages.company_name') }}:</label>
        <div class="input-group">
            <input id="company_name" name="company_name" type="text"
                class="form-control form-control-prepended @error('company_name') is-invalid @enderror"
                placeholder="{{ __('messages.company_name') }}" value="{{ old('company_name') }}"
                required>
            @error('company_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label class="text-label" for="email">{{ __('messages.email') }}:</label>
        <div class="input-group">
            <input id="email" name="email" type="email"
                class="form-control form-control-prepended @error('email') is-invalid @enderror"
                placeholder="user@example.com" value="{{ old('email') }}" autocomplete="email" required>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label class="text-label" for="password">{{ __('messages.password') }}:</label>
        <div class="input-group">
            <input id="password" name="password" type="password"
                class="form-control form-control-prepended @error('password') is-invalid @enderror"
                placeholder="{{ __('messages.enter_your_password') }}" required>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label class="text-label" for="password_confirmation">{{ __('messages.confirm_password') }}:</label>
        <div class="input-group">
            <input id="password_confirmation" name="password_confirmation" type="password"
                class="form-control form-control-prepended @error('password_confirmation') is-invalid @enderror"
                placeholder="{{ __('messages.confirm_password') }}" required>
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        @if(get_system_setting('google_recapthca_key'))
            <button class="btn btn-block btn-primary g-recaptcha" data-sitekey="{{ get_system_setting('google_recapthca_key') }}" data-callback="onSubmit" data-action="submit">{{ __('messages.register') }}</button>
        @else
            <button class="btn btn-block btn-primary" type="submit">{{ __('messages.register') }}</button>
        @endif
    </div>

    <div class="form-group text-center">
        <a href="{{ route('login') }}">{{ __('messages.login') }}</a> <br>
    </div>

    @if(\App\Models\SystemSetting::isTermsActive())
        <div class="form-group text-center">
            <p>{!! __('messages.by_clicking_register_term') !!}</p>
        </div>
    @endif
</form>

@endsection

@section('page_body_scripts')
    <script src="https://www.google.com/recaptcha/api.js"></script>
    @if(get_system_setting('google_recapthca_key'))
        <script>
            function onSubmit(token) {
                document.getElementById("auth-form").submit();
            }
        </script>
    @endif
@endsection