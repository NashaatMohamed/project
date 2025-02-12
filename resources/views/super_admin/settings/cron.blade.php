@extends('layouts.app', ['page' => 'super_admin.settings.cron_configuration'])

@section('title', __('messages.cron_configuration'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a>{{ __('messages.cron_configuration') }}</a></li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.cron_configuration') }}</h1>
        </div>
    </div>
@endsection
 
@section('content') 
    <div class="card p-5">
        <h3>{{ __('messages.copy_cron_description') }}</h3>
        <code class="mt-4">{{ PHP_BINARY }} {{ base_path('artisan') }} schedule:run >> /dev/null 2>&1</code>
        <p class="mt-4">To learn more, check the documentation at <a href="https://docs-foxtrot.varuscreative.com/configuration/configuration-1" target="_blank">Docs</a></p>
    </div>
@endsection  