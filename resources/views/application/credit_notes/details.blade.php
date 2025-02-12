@extends('layouts.app', ['page' => 'credit_notes'])

@section('title', __('messages.credit_note_details'))
 
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('credit_notes', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.credit_notes') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.credit_note_details') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.credit_note_details') }}</h1>
        </div>
    </div>
@endsection
 
@section('content') 
    <div class="row">
        <div class="col-12 col-md-4">
            <p class="h2 pb-4">
                #{{ $credit_note->credit_note_number }}
            </p>
        </div>
        <div class="col-12 col-md-8 text-right">
            <div class="btn-group mb-2">
                <a href="{{ route('pdf.credit_note', ['credit_note' => $credit_note->uid, 'download' => true]) }}" target="_blank" class="btn btn-light">
                    <i class="material-icons">cloud_download</i> 
                    {{ __('messages.download') }}
                </a>
                <a href="{{ route('credit_notes.send', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-light alert-confirm" data-alert-title="Are you sure?" data-alert-text="This action will send an email to customer.">
                    <i class="material-icons">send</i>
                    {{ __('messages.send_email') }}
                </a>
                <a href="{{ route('payments.create', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]) }}" target="_blank" class="btn btn-light">
                    <i class="material-icons">payment</i> 
                    {{ __('messages.apply_to_invoice') }}
                </a>
                <a href="{{ route('credit_notes.edit', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-light">
                    <i class="material-icons">edit</i> 
                    {{ __('messages.edit') }}
                </a>
                <div class="btn-group">
                    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        {{ __('messages.more') }} <span class="caret"></span> 
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('customer_portal.credit_notes.details', ['credit_note' => $credit_note->uid, 'customer' => $credit_note->customer->uid]) }}" class="dropdown-item" target="_blank">{{ __('messages.share') }}</a>
                        <a href="{{ route('credit_notes.refund', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]) }}" class="dropdown-item">{{ __('messages.refund') }}</a>
                        <a href="{{ route('credit_notes.mark', ['credit_note' => $credit_note->id, 'status' => 'sent', 'company_uid' => $currentCompany->uid]) }}" class="dropdown-item">{{ __('messages.mark_sent') }}</a>
                        <hr>
                        <a href="{{ route('credit_notes.delete', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]) }}" class="dropdown-item text-danger delete-confirm">{{ __('messages.delete') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            @if($credit_note->status == 'DRAFT')
                <div class="alert alert-soft-dark d-flex align-items-center" role="alert">
                    <i class="material-icons mr-3">access_time</i>
                    <div class="text-body"><strong>{{ __('messages.status') }} : </strong> {{ __('messages.draft') }}</div>
                </div>
            @elseif($credit_note->status == 'SENT')
                <div class="alert alert-soft-info d-flex align-items-center" role="alert">
                    <i class="material-icons mr-3">send</i>
                    <div class="text-body"><strong>{{ __('messages.status') }} : </strong> {{ __('messages.mailed_to_customer') }}</div>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6 order-2 order-md-1">
            <div class="pdf-iframe">
                <iframe src="{{ route('pdf.credit_note', $credit_note->uid) }}" frameborder="0"></iframe>
            </div>
        </div>
        <div class="col-12 col-md-6 order-1 order-md-2">
            <nav class="nav nav-pills nav-justified w-100" role="tablist">
                <a href="#applied_invoices" class="h6 nav-item nav-link bg-secondary text-white active show" data-toggle="tab" role="tab" aria-selected="true">{{ __('messages.applied_invoices') }}</a>
                <a href="#refunds" class="h6 nav-item nav-link bg-secondary text-white" data-toggle="tab" role="tab" aria-selected="false">{{ __('messages.refunds') }}</a>
            </nav>
        
            <div class="tab-content">
                <div class="tab-pane active show" id="applied_invoices">
                    <div class="card">
                        <div class="mt-3 mb-3">
                            @if($payments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table mb-0 thead-border-top-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.date') }}</th>
                                                <th>{{ __('messages.invoice') }}</th>
                                                <th>{{ __('messages.amount') }}</th>
                                                <th class="w-50px">{{ __('messages.view') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list" id="payments">
                                            @foreach ($payments as $payment)
                                                <tr>
                                                    <td>
                                                        {{ $payment->formatted_payment_date }}
                                                    </td>
                                                    <td>
                                                        @if ($payment->invoice)
                                                            <a href="{{ route('invoices.details', ['invoice' => $payment->invoice->id, 'company_uid' => $currentCompany->uid]) }}" target="_blank">{{ $payment->invoice->invoice_number }}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {!! money($payment->amount, $payment->currency_code) !!}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('payments.edit', ['payment' => $payment->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-sm btn-link">
                                                            <i class="material-icons icon-16pt">arrow_forward</i>
                                                        </a> 
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row card-body pagination-light justify-content-center text-center">
                                    {{ $payments->links() }}
                                </div>
                            @else
                                <div class="row justify-content-center card-body pb-0 pt-5">
                                    <i class="material-icons fs-64px">payment</i>
                                </div>
                                <div class="row justify-content-center card-body pb-5">
                                    <p class="h4">{{ __('messages.no_invoices_yet') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="refunds">
                    <div class="card">
                        <div class="mt-3 mb-3">
                            @if($refunds->count() > 0)
                                <div class="table-responsive">
                                    <table class="table mb-0 thead-border-top-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.date') }}</th>
                                                <th>{{ __('messages.amount') }}</th>
                                                <th>{{ __('messages.payment_method') }}</th>
                                                <th class="w-50px">{{ __('messages.delete') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list" id="refunds_table">
                                            @foreach ($refunds as $refund)
                                                <tr>
                                                    <td>
                                                        {{ $refund->formatted_refund_date }}
                                                    </td>
                                                    <td>
                                                        {!! money($refund->amount, $refund->currency_code) !!}
                                                    </td>
                                                    <td>
                                                        {{ $refund->payment_method->name ?? "-" }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('credit_notes.refund.delete', ['company_uid' => $currentCompany->uid, 'credit_note' => $credit_note->id, 'refund' => $refund->id]) }}" class="btn btn-sm btn-link text-danger delete-confirm">
                                                            <i class="material-icons icon-16pt">close</i>
                                                        </a> 
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row card-body pagination-light justify-content-center text-center">
                                    {{ $refunds->links() }}
                                </div>
                            @else
                                <div class="row justify-content-center card-body pb-0 pt-5">
                                    <i class="material-icons fs-64px">payment</i>
                                </div>
                                <div class="row justify-content-center card-body pb-5">
                                    <p class="h4">{{ __('messages.no_refunds_yet') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
