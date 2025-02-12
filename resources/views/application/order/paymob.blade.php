@extends('layouts.onboard')

@section('title', __('messages.checkout'))

@section('content')
    <div class="page__heading">
        <h1>{{ __('messages.checkout') }}</h1>
    </div> 

    <div class="row card-group-row pt-2">
        <div class="col-12">
            <div class="card-body">
                @if ($iframe_id)
                    <iframe class="paymob-iframe" src="https://accept.paymob.com/api/acceptance/iframes/{{ $iframe_id }}?payment_token={{ $payment_key->token }}"></iframe>
                @else
                    <div class="card-text">
                        <form action="{{ route('order.payment.paymob.wallet_payment', ['plan' => $plan->slug]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $payment_key->token }}">

                            <div class="form-group">
                                <label for="wallet_number">{{ __('messages.wallet_number') }}</label>
                                <input class="form-control" type="text" name="wallet_number" placeholder="{{ __('messages.wallet_number') }}"> 
                            </div>

                            <div class="d-flex flex-row mt-4 justify-content-end align-items-center">
                                <button id="card-button" class="btn btn-primary">
                                    {{ __('messages.pay') }} ({!! money($plan->price, $plan->currency) !!})
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection