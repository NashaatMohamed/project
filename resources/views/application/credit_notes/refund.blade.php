@extends('layouts.app', ['page' => 'credit_notes'])

@section('title', __('messages.refund'))
 
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('credit_notes', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.credit_notes') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('credit_notes.details', ['company_uid' => $currentCompany->uid, 'credit_note' => $credit_note->id]) }}">{{ $credit_note->display_name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.refund') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.refund') }}</h1>
        </div>
    </div>
@endsection
 
@section('content') 
    <form action="{{ route('credit_notes.refund.store', ['company_uid' => $currentCompany->uid, 'credit_note' => $credit_note->id]) }}" method="POST">
        @include('layouts._form_errors')
        @csrf
        
        <div class="card card-form">
            <div class="row no-gutters">
                <div class="col-lg-4 card-body">
                    <p><strong class="headings-color">{{ __('messages.refund_information') }}</strong></p>
                </div>
                <div class="col-lg-8 card-form__body card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group required">
                                <label for="refund_date">{{ __('messages.date') }}</label>
                                <input name="refund_date" type="text" class="form-control input" data-toggle="flatpickr" data-flatpickr-default-date="{{ now() }}" placeholder="{{ __('messages.date') }}" readonly="readonly" required>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col">
                            <div class="form-group required">
                                <label for="amount">{{ __('messages.amount') }}</label>
                                <input id="amount" name="amount" type="text" class="form-control price_input" placeholder="{{ __('messages.amount') }}" autocomplete="off" value="{{ $payment->amount ?? 0 }}" required>
                                <small>{{ __('messages.available_credit') }}: {!! money($credit_note->remaining_balance, $credit_note->currency_code) !!}</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group select-container required">
                                <label for="payment_method_id">{{ __('messages.payment_type') }}</label>
                                <select id="payment_method_id" name="payment_method_id" data-toggle="select" class="form-control select2-hidden-accessible select-with-footer" data-minimum-results-for-search="-1" data-select2-id="payment_method_id">
                                    <option disabled selected>{{ __('messages.select_payment_type') }}</option>
                                    @foreach(get_payment_methods_select2_array($currentCompany->id) as $option)
                                        <option value="{{ $option['id'] }}">{{ $option['text'] }}</option>
                                    @endforeach
                                </select>
                                <div class="d-none select-footer">
                                    <a href="{{ route('settings.payment.type.create', ['company_uid' => $currentCompany->uid]) }}" target="_blank" class="font-weight-300">+ {{ __('messages.add_new_payment_method') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="notes">{{ __('messages.notes') }}</label>
                                <textarea name="notes" class="form-control" rows="4" placeholder="{{ __('messages.notes') }}"></textarea>
                            </div>
                        </div>
                    </div>
        
                    <div class="form-group text-center mt-3">
                        <button type="button" class="btn btn-primary form_with_price_input_submit">{{ __('messages.refund') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('page_body_scripts')
    <script>
        $(document).ready(function(){
            var currency = @json($credit_note->customer->currency);
            setupPriceInput(currency);
        });
    </script>
@endsection