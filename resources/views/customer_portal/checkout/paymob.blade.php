@extends('layouts.customer_portal', ['page' => 'invoices'])

@section('title', __('messages.checkout'))

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ __('messages.portal') }}</li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('customer_portal.invoices', $currentCustomer->uid) }}">{{ __('messages.invoices') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('customer_portal.invoices.details', [$currentCustomer->uid, $invoice->uid]) }}">{{ $invoice->invoice_number }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.checkout') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.checkout') }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="row  justify-content-center">
        <div class="col-12 col-md-4">
            <div class="card">
                <h4 class="card-header">{{ __('messages.invoice') }}</h4>
                <div class="card-body">
                    <p class="h6"><strong>{{ $invoice->invoice_number }}</strong></p>
                    <p class="h6"><strong>{{ __('messages.notes') }} :</strong> {{ $invoice->notes ?? '-' }}</p>
                    <p class="h6"><strong>{{ __('messages.amount') }} :</strong> {!! money($invoice->due_amount, $invoice->currency_code) !!}</p>
                    <p class="h6"><strong>{{ __('messages.fee') }} :</strong> {!! money($fee, $invoice->currency_code) !!}</p>
                    <hr>
                    <p class="h5"><strong>{{ __('messages.total') }} :</strong> {!! money($total, $invoice->currency_code) !!}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="card">
                <h4 class="card-header">{{ __('messages.checkout') }}</h4>
                <div class="card-body">
                    @if ($iframe_id)
                        <iframe class="paymob-iframe" src="https://accept.paymob.com/api/acceptance/iframes/{{ $iframe_id }}?payment_token={{ $payment_key->token }}"></iframe>
                    @else
                        <div class="card-text">
                            <form action="{{ route('customer_portal.invoices.paymob.wallet_payment', ['customer' => $currentCustomer->uid, 'invoice' => $invoice->uid]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $payment_key->token }}">

                                <div class="form-group">
                                    <label for="wallet_number">{{ __('messages.wallet_number') }}</label>
                                    <input class="form-control" type="text" name="wallet_number" placeholder="{{ __('messages.wallet_number') }}"> 
                                </div>

                                <div class="d-flex flex-row mt-4 justify-content-end align-items-center">
                                    <button id="card-button" class="btn btn-primary">
                                        {{ __('messages.pay') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection