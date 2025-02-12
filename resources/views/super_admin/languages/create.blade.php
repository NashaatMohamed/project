@extends('layouts.app', ['page' => 'super_admin.languages'])

@section('title', __('messages.add_language'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a>{{ __('messages.languages') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a>{{ __('messages.add_language') }}</a></li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.add_language') }}</h1>
        </div>
    </div>
@endsection

@section('content') 
    <form action="{{ route('super_admin.languages.store') }}" method="POST">
        @include('layouts._form_errors')
        @csrf
        
        <div class="card card-form">
            <div class="row no-gutters">
                <div class="col-lg-4 card-body">
                    <p><strong class="headings-color">{{ __('messages.language_information') }}</strong></p>
                </div>
                <div class="col-lg-8 card-form__body card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group required">
                                <label for="name">{{ __('messages.name') }}</label>
                                <input name="name" type="text" class="form-control" placeholder="{{ __('messages.name') }}" required>
                            </div>
                        </div> 
                        <div class="col">
                            <div class="form-group required">
                                <label for="description">{{ __('messages.locale') }}</label>
                                <input name="locale" type="text" class="form-control" placeholder="{{ __('messages.locale') }}" required>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group text-center mt-5">
                        <button class="btn btn-primary save_form_button">{{ __('messages.add_language') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection