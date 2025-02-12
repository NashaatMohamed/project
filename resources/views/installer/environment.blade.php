@extends('layouts.installer')

@section('title', __('Step 3 | Application Settings'))

@section('content')
    <form method="POST" action="{{ route('installer.environment.save') }}">
        @csrf

        <div class="form-group mb-4">
            <label class="form-label" for="app_name">{{ __('Application Name') }}</label>
            <input class="form-control rounded-sm @error('app_name') is-invalid @enderror" type="text" name="app_name" value="{{ env('APP_NAME') }}">
            @error('app_name')<small class="form-text text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" for="app_url">{{ __('Application Url') }}</label>
            <input class="form-control rounded-sm" type="text" name="app_url" value="http://example.com">
            <small class="form-text">{{ __('Enter the full domain address without "/" at the end.') }}</small>
            @error('app_url')<small class="form-text text-danger">{{ $message }}</small>@enderror
        </div>
        <hr class="my-3">

        <h6>{{ __('Database Configuration') }}</h6>
        <div class="form-group mb-4">
            <label class="form-label" for="database_connection">{{ __('Connection') }}</label>
            <select class="form-control rounded-sm" name="database_connection" id="database_connection">
                <option value="mysql" selected>{{ __('mysql') }}</option>
            </select>
            @error('database_connection')<small class="form-text text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" for="database_hostname">{{ __('Database Host') }}</label>
            <input class="form-control rounded-sm" type="text" name="database_hostname" value="127.0.0.1">
            @error('database_hostname')<small class="form-text text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" for="database_port">{{ __('Database Port') }}</label>
            <input class="form-control rounded-sm" type="text" name="database_port" value="3306">
            @error('database_port')<small class="form-text text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" for="database_name">{{ __('Database Name') }}</label>
            <input class="form-control rounded-sm" type="text" name="database_name" value="">
            @error('database_name')<small class="form-text text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" for="database_username">{{ __('Database Username') }}</label>
            <input class="form-control rounded-sm" type="text" name="database_username" value="">
            @error('database_username')<small class="form-text text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" for="database_password">{{ __('Database Password') }}</label>
            <input class="form-control rounded-sm" type="password" name="database_password" value="">
            @error('database_password')<small class="form-text text-danger">{{ $message }}</small>@enderror
        </div>
        <hr class="my-3">

        <button class="btn btn-primary px-4 fs-6" type="submit">
            {{ __('Install') }}
        </button>
    </form>
@endsection
