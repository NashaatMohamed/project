@extends('layouts.app', ['page' => 'settings'])

@section('title', __('messages.warehouses'))
    
@section('content')
    <div class="page__heading">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">business</i></a></li>
                <li class="breadcrumb-item">{{ __('messages.settings') }}</li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.warehouses') }}</li>
            </ol>
        </nav>
        <h1 class="m-0">{{ __('messages.warehouses') }}</h1>
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
                                    <strong class="headings-color">{{ __('messages.warehouses') }}</strong>
                                </p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('settings.warehouse.create', ['company_uid' => $currentCompany->uid]) }}" class="btn btn-primary text-white">
                                    {{ __('messages.add_warehouse') }}
                                </a>
                            </div>
                        </div>

                        @if($warehouses->count() > 0)
                            <div class="table-responsive" data-toggle="lists">
                                <table class="table table-xl mb-0 thead-border-top-0 table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.name') }}</th>
                                            <th class="w-30">{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list" id="warehouse">
                                        @foreach($warehouses as $warehouse)
                                            <tr>
                                                <td class="h6">
                                                    <a href="{{ route('settings.warehouse.edit', ['warehouse' => $warehouse->id, 'company_uid' => $currentCompany->uid]) }}">
                                                        <strong class="h6">
                                                            {{ $warehouse->name }}
                                                        </strong>
                                                    </a>
                                                </td>

                                                <td class="h6">
                                                    <a href="{{ route('settings.warehouse.edit', ['warehouse' => $warehouse->id, 'company_uid' => $currentCompany->uid]) }}" class="btn text-primary">
                                                        <i class="material-icons icon-16pt">edit</i>
                                                        {{ __('messages.edit') }}
                                                    </a>
                                                    <a href="{{ route('settings.warehouse.delete', ['warehouse' => $warehouse->id, 'company_uid' => $currentCompany->uid]) }}" class="btn text-danger delete-confirm">
                                                        <i class="material-icons icon-16pt">delete</i>
                                                        {{ __('messages.delete') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row card-body pagination-light justify-content-center text-center">
                                {{ $warehouses->links() }}
                            </div>
                        @else
                            <div class="row justify-content-center card-body pb-0 pt-5">
                                <i class="material-icons fs-64px">account_balance_wallet</i>
                            </div>
                            <div class="row justify-content-center card-body pb-5">
                                <p class="h4">{{ __('messages.no_warehouse') }}</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

