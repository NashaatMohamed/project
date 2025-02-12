@extends('layouts.app', ['page' => 'super_admin.settings.currencies'])

@section('title', __('messages.currencies'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a>{{ __('messages.currencies') }}</a></li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.currencies') }}</h1>
        </div>
    </div>
@endsection
 
@section('content') 
    <div class="card">
        @if($currencies->count() > 0)
            <div class="table-responsive">
                <table class="table mb-0 thead-border-top-0 table-striped">
                    <thead>
                        <tr>
                            <th class="w-30px" class="text-center">{{ __('messages.id') }}</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.code') }}</th>
                            <th>{{ __('messages.symbol') }}</th>
                            <th class="text-center">{{ __('messages.status') }}</th>
                        </tr> 
                    </thead> 
                    <tbody class="list" id="currencies">
                        @foreach ($currencies as $currency)
                            <tr>
                                <td>
                                    <p class="mb-0">{{ $currency->id }}</p>
                                </td>
                                <td>
                                    <p class="mb-0">{{ $currency->name }}</p>
                                </td>
                                <td>
                                    <p class="mb-0">{{ $currency->short_code }}</p>
                                </td>
                                <td>
                                    <p class="mb-0">{{ $currency->symbol }}</p>
                                </td>
                                <td class="text-center">
                                    @if ($currency->enabled)
                                        <a class="btn btn-link text-danger" href="{{ route('super_admin.settings.currencies.disable', $currency->short_code) }}">Disable</a>
                                    @else
                                        <a class="btn btn-link text-primary" href="{{ route('super_admin.settings.currencies.enable', $currency->short_code) }}">Enable</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(method_exists($currencies, 'links'))
                <div class="row card-body pagination-light justify-content-center text-center">
                    {{ $currencies->links() }}
                </div>
            @endif
        @else
            <div class="row justify-content-center card-body pb-0 pt-5">
                <i class="material-icons fs-64px">account_box</i>
            </div>
            <div class="row justify-content-center card-body pb-5">
                <p class="h4">{{ __('messages.no_currencies_yet') }}</p>
            </div>
        @endif
    </div>
@endsection  