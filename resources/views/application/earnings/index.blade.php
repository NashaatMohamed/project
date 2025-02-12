@extends('layouts.app', ['page' => 'earnings'])

@section('title', __('messages.earnings'))

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.earnings') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.earnings') }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        @include('application.earnings._tabs')

        <div class="list-group tab-content list-group-flush">
            @foreach ($wallets as $wallet)
                <div class="list-group-item list-group-item-action d-flex align-items-center ">
                    <div class="avatar mr-3">
                        <span class="avatar-title rounded-circle bg-dark-grey">
                            <span class="h4 text-white mb-0">{!! currency($wallet->name)->getSymbol() !!}</span>
                        </span>
                    </div>
                
                    <div class="flex">
                        <div class="d-flex align-items-middle">
                            <strong class="text-15pt mr-1">{!! money($wallet->balance, $wallet->name) !!}</strong>
                        </div>
                        <small class="text-muted">{{ currency($wallet->name) }}</small>
                    </div>
                    <a class="btn-link" href="{{ route('earnings.withdraw', ['company_uid' => $currentCompany->uid, 'code' => $wallet->name]) }}">{{ __('messages.withdraw') }}</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
