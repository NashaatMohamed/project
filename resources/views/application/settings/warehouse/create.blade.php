@extends('layouts.app', ['page' => 'settings'])

@section('title', __('messages.add_warehouse'))
    
@section('content')
    <div class="page__heading">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">business</i></a></li>
                <li class="breadcrumb-item">{{ __('messages.settings') }}</li>
                <li class="breadcrumb-item"><a href="{{ route('settings.warehouse', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.warehouse') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.add_warehouse') }}</li>
            </ol>
        </nav>
        <h1 class="m-0">{{ __('messages.add_warehouse') }}</h1>
    </div>
 
    <div class="row">
        <div class="col-lg-3">
            @include('application.settings._aside', ['tab' => 'warehouse'])
        </div>
        <div class="col-lg-9">
            <div class="card card-form">
                <div class="row no-gutters">
                    <div class="col card-form__body card-body bg-white">
                        <div class="form-row align-items-center mb-4">
                            <div class="col">
                                <p class="h4 mb-0">
                                    <strong class="headings-color">{{ __('messages.add_warehouse') }}</strong>
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('settings.warehouse.store', ['company_uid' => $currentCompany->uid]) }}" method="POST">
                            @include('layouts._form_errors')
                            @csrf
                            
                            @include('application.settings.warehouse._form')

                            <div class="form-group text-right mt-4">
                                <button type="submit" class="btn btn-primary">{{ __('messages.add_warehouse') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

