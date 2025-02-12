@extends('layouts.auth')

@section('title', __('messages.login'))

@section('content')
<h1 class="text-center h6 mb-4">{{ __('messages.login_to_your_account') }}</h1>

<form id="auth-form" action="{{ route('login') }}" method="POST" novalidate>
    @csrf
    @honeypot
    
    @if($errors->has('recaptcha_token'))
        <span class="invalid-feedback" role="alert">
            <strong>{{$errors->first('recaptcha_token')}}</strong>
        </span>
    @endif

    <div class="form-group">
        <label class="text-label" for="email">{{ __('messages.email') }}:</label>
        <div class="input-group input-group-merge">
            <input id="email" name="email" type="email"
                class="form-control form-control-prepended @error('email') is-invalid @enderror"
                placeholder="user@example.com" value="{{ old('email') }}" autocomplete="email"
                autofocus required>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <span class="far fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

    </div>
    <div class="form-group">
        <label class="text-label" for="password">{{ __('messages.password') }}:</label>
        <div class="input-group input-group-merge">
            <input id="password" name="password" type="password"
                class="form-control form-control-prepended @error('password') is-invalid @enderror"
                placeholder="{{ __('messages.enter_your_password') }}" required autocomplete="current-password">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <span class="fa fa-key"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        @if(get_system_setting('google_recapthca_key'))
            <button class="btn btn-block btn-primary g-recaptcha" data-sitekey="{{ get_system_setting('google_recapthca_key') }}" data-callback="onSubmit" data-action="submit">{{ __('messages.login') }}</button>
        @else
            <button class="btn btn-block btn-primary" type="submit">{{ __('messages.login') }}</button>
        @endif
    </div>

    <div class="form-group text-center">
        <div class="custom-control custom-checkbox">
            <input id="remember" name="remember" type="checkbox" class="custom-control-input" checked="">
            <label class="custom-control-label" for="remember">{{ __('messages.remember_me') }}</label>
        </div>
    </div>

    <div class="form-group text-center">
        <a href="{{ route('password.request') }}">{{ __('messages.forgot_your_password') }}</a> <br>
    </div>

    <div class="form-group text-center">
        <a href="{{ route('register') }}">{{ __('messages.register') }}</a> <br>
    </div>
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