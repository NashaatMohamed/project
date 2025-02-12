@extends('layouts.app', ['page' => 'super_admin.withdraw_requests'])

@section('title', __('messages.edit_withdraw_request'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit_withdraw_request') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.edit_withdraw_request') }}</h1>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('super_admin.withdraw_requests.decline', $withdraw_request->id) }}" method="POST">
        @include('layouts._form_errors')
        @csrf
        
        <div class="card">
            <div class="card card-form" style="margin-bottom: 0px">
                <div class="row no-gutters">
                    <div class="col-lg-4 card-body">
                        <p><strong class="headings-color">{{ __('messages.withdraw_request_information') }}</strong></p>
                        <p>{{ __('messages.status') }}: {!! $withdraw_request->html_status !!}</p>
                        <p>{{ __('messages.managed_by') }}: {{ $withdraw_request->approved_by_user->full_name ?? '-' }}</p>
                        <p>{{ __('messages.approved_at') }}: {{ $withdraw_request->approved_at ?? '-' }}</p>
                        <p>{{ __('messages.declined_at') }}: {{ $withdraw_request->declined_at ?? '-' }}</p>
                    </div>
                    <div class="col-lg-8 card-form__body card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('messages.company') }}</label>
                                    <input type="text" class="form-control" value="{{ $withdraw_request->company->name }}" disabled>
                                </div>
                            </div>
    
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('messages.requested_by') }}</label>
                                    <input type="text" class="form-control" value="{{ $withdraw_request->requested_by_user->full_name }} - {{ $withdraw_request->requested_by_user->email }}" disabled>
                                </div>
                            </div>
    
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('messages.current_company_balance') }}</label>
                                    <input type="text" class="form-control" value="{{ number_format($withdraw_request->wallet->balance/100, 2, '.', '') }} {{ $withdraw_request->wallet_currency }}" disabled>
                                    <small class="form-text text-muted">{{ __('messages.current_company_balance_description') }}</small>
                                </div>
                            </div>
    
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('messages.amount_to_decrease_from_wallet') }}</label>
                                    <input type="text" class="form-control" value="{{ number_format($withdraw_request->amount_to_decrease/100, 2, '.', '') }} {{ $withdraw_request->wallet_currency }}" disabled>
                                    <small class="form-text text-muted">{{ __('messages.amount_to_decrease_from_wallet_description') }}</small>
                                </div>
                            </div>
    
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('messages.amount_to_deposit') }}</label>
                                    <input type="text" class="form-control" value="{{ $withdraw_request->amount_to_deposit }} {{ $withdraw_request->wallet_currency }}" disabled>
                                    <small class="form-text text-muted">
                                        {{ __('messages.commission') }}: @if($commissions['withdraw_fixed_fee']) {{ $commissions['withdraw_fixed_fee'] }} @endif  @if($commissions['withdraw_percent_fee']) + %{{ $commissions['withdraw_percent_fee'] }} @endif
                                    </small>
                                </div>
                            </div>
    
                            @if($withdraw_request->notes)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>{{ __('messages.withdraw_request_note') }}</label>
                                        <textarea class="form-control" rows="5" disabled>{{ $withdraw_request->notes }}</textarea>
                                    </div>
                                </div>
                            @endif
    
                            <div class="col-12"><hr class="mt-3 mb-3"></div>
    
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('messages.bank') }}</label>
                                    <input type="text" class="form-control" value="{{ optional(optional(optional($withdraw_request->withdraw_account))->bank)->name }}" disabled>
                                </div>
                            </div>
    
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('messages.iban') }}</label>
                                    <input type="text" class="form-control" value="{{ optional($withdraw_request->withdraw_account)->iban }}" disabled>
                                </div>
                            </div>
    
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ __('messages.bank_account_holder') }}</label>
                                    <input type="text" class="form-control" value="{{ optional($withdraw_request->withdraw_account)->full_name }}" disabled>
                                </div>
                            </div>
    
                            @if(optional($withdraw_request->withdraw_account)->additional_info)
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>{{ __('messages.additional_bank_information') }}</label>
                                        <textarea class="form-control" rows="5" disabled>{{ optional($withdraw_request->withdraw_account)->additional_info }}</textarea>
                                    </div>
                                </div>
                            @endif
    
                            <div class="col-12"><hr class="mt-3 mb-3"></div>
    
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="declined_reason">{{ __('messages.declined_reason') }}</label>
                                    <textarea name="declined_reason" class="form-control" rows="5"></textarea>
                                    <small class="form-text text-muted">{{ __('messages.declined_reason_description') }}</small>
                                </div>
                            </div>
                        </div>
    
                        @if($withdraw_request->status == \App\Models\WithdrawRequest::STATUS_REQUESTED)
                            <div class="form-group text-center mt-5">
                                <a href="{{ route('super_admin.withdraw_requests.approve', $withdraw_request->id) }}" class="btn btn-primary">{{ __('messages.approve') }}</a>
                                <button class="btn btn-danger" type="submit">{{ __('messages.decline') }}</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
