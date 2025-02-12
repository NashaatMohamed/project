@extends('layouts.app', ['page' => 'settings'])

@section('title', __('messages.edit_variation_group'))

@section('css_custom')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: #f5f5f5;
        }

        .sortable-chosen {
            background: #f9f9f9;
        }

        .sortable-drag {
            background: #fff;
        }

    </style>

@endsection

@section('content')
    <div class="page__heading">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">business</i></a></li>
                <li class="breadcrumb-item">{{ __('messages.settings') }}</li>
                <li class="breadcrumb-item"><a href="{{ route('settings.variation_group', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.variation_group') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit_variation_group') }}</li>
            </ol>
        </nav>
        <h1 class="m-0">{{ __('messages.edit_variation_group') }}</h1>
    </div>
 
    <div class="row">
        <div class="col-lg-3">
            @include('application.settings._aside', ['tab' => 'variation_group'])
        </div>
        <div class="col-lg-9">
            <div class="card card-form">
                <div class="row no-gutters">
                    <div class="col card-form__body card-body bg-white">
                        <div class="form-row align-items-center mb-4">
                            <div class="col">
                                <p class="h4 mb-0">
                                    <strong class="headings-color">{{ __('messages.edit_variation_group') }}</strong>
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('settings.variation_group.update', ['variation_group' => $variation_group->id, 'company_uid' => $currentCompany->uid]) }}" method="POST">
                            @include('layouts._form_errors')
                            @csrf

                            @include('application.settings.variation_group._form')

                            <div class="form-group text-right mt-4">
                                <button type="submit" class="btn btn-primary">{{ __('messages.update_variation_group') }}</button>
                                <a href="{{ route('settings.variation_group.delete', ['variation_group' => $variation_group->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-light text-danger delete-confirm">
                                    <i class="material-icons icon-16pt">delete</i>
                                    {{ __('messages.delete') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card card-form">
                <div class="row no-gutters">
                    <div class="col card-form__body card-body bg-white">
                        <div class="form-row align-items-center mb-4">
                            <div class="col">
                                <p class="h4 mb-0">
                                    <strong class="headings-color">{{ __('messages.add_variations') }}</strong>
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('settings.group_variation.update', ['variation_group' => $variation_group->id, 'company_uid' => $currentCompany->uid]) }}" method="POST">
                            @include('layouts._form_errors')
                            @csrf

                            @include('application.settings.variation_group.variations_form')

                            <div class="form-group text-right mt-4">
                                <button type="submit" class="btn btn-primary">{{ __('messages.update_variations') }}</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

