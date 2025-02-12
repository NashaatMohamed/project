@extends('layouts.app', ['page' => 'earnings'])

@section('title', __('messages.withdraw'))

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('earnings', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.earnings') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.withdraw') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.withdraw') }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('earnings.withdraw', ['company_uid' => $currentCompany->uid, 'code' => $wallet->name]) }}" method="POST" enctype="multipart/form-data">
        @include('layouts._form_errors')
        @csrf
        <div class="card card-form">
            <div class="row no-gutters">
                <div class="col-lg-4 card-body">
                    <p><strong class="headings-color">{{ __('messages.withdraw_information') }}</strong></p>
                    <p>{{ __('messages.withdraw_limit') }}: {{ $withdraw_limit }}</p>
                </div>
                <div class="col-lg-8 card-form__body card-body">
                    @if($error_msg)
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    <h4 class="alert-heading">{{ __('messages.oops') }}</h4>
                                    <p>{{ $error_msg }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12"> 
                                <div class="form-group select-container required">
                                    <label for="account_id">{{ __('messages.bank') }}</label>
                                    <select name="account_id" data-toggle="select" class="form-control select2-hidden-accessible select-with-footer" data-select2-id="account_id" required>
                                        <option disabled selected>{{ __('messages.select_bank') }}</option>
                                        @foreach($withdraw_accounts as $option)
                                            <option value="{{ $option->id }}">{{ $option->bank->name }} - {{ $option->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="d-none select-footer">
                                        <a href="{{ route('settings.payment.account.create', ['company_uid' => $currentCompany->uid]) }}" target="_blank" class="font-weight-300">+ {{ __('messages.add_new_bank_account') }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6"> 
                                <div class="form-group">
                                    <label>{{ __('messages.balance') }}</label>
                                    <input type="text" class="form-control price_input" autocomplete="off" value="{{ $wallet->balance }}" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-6"> 
                                <div class="form-group">
                                    <label>{{ __('messages.amount_to_deposit') }}</label>
                                    <input type="text" class="form-control price_input" autocomplete="off" value="{{ $amount_to_deposit }}" readonly>
                                    <small class="form-text text-muted">
                                        {{ __('messages.commission') }}: @if($commissions['withdraw_fixed_fee']) {{ $commissions['withdraw_fixed_fee'] }} @endif  @if($commissions['withdraw_percent_fee']) + %{{ $commissions['withdraw_percent_fee'] }} @endif
                                    </small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">{{ __('messages.notes') }}</label>
                                    <textarea name="notes" class="form-control" cols="30" rows="3" placeholder="{{ __('messages.notes') }}"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group text-center mt-3">
                            <button type="button" class="btn btn-primary form_with_price_input_submit">{{ __('messages.withdraw') }}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection

@section('page_body_scripts')
    <script>
        $(document).ready(function() {
            // Setup currency
            setupPriceInput(window.sharedData.withdraw_currency);
        });
    </script>
@endsection