<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @include('layouts._css')
</head>

<body class="layout-login-centered-boxed"> 
    <div class="layout-login-centered-boxed__form card">
        <div class="d-flex flex-column justify-content-center align-items-center mt-2 mb-2 navbar-light">
            <a class="navbar-brand flex-column mb-2 align-items-center mr-0">
                <span>@yield('title')</span>
            </a>
        </div>
        @include('layouts._form_errors')

        @yield('content')
    </div>

    @include('layouts._js')
    @include('layouts._flash')
</body>
</html>