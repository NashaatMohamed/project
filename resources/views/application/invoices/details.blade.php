@extends('layouts.app', ['page' => 'invoices'])

@section('title', __('messages.invoice_details'))
 
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('invoices', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.invoices') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.invoice_details') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.invoice_details') }}</h1>
        </div>
    </div>
@endsection
 
@section('content') 
    <div class="row">
        <div class="col-12 col-md-4">
            <p class="h2 pb-4">
                #{{ $invoice->invoice_number }}
            </p>
        </div>
        <div class="col-12 col-md-8 text-right">
            <div class="btn-group mb-2">
                <a href="{{ route('pdf.invoice', ['invoice' => $invoice->uid, 'download' => true]) }}" target="_blank" class="btn btn-light">
                    <i class="material-icons">cloud_download</i> 
                    {{ __('messages.download') }}
                </a>
                <a href="{{ route('invoices.send', ['invoice' => $invoice->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-light alert-confirm" data-alert-title="Are you sure?" data-alert-text="This action will send an email to customer.">
                    <i class="material-icons">send</i>
                    {{ __('messages.send_email') }}
                </a>
                <a href="{{ route('payments.create', ['invoice' => $invoice->id, 'company_uid' => $currentCompany->uid]) }}" target="_blank" class="btn btn-light">
                    <i class="material-icons">payment</i> 
                    {{ __('messages.enter_payment') }}
                </a>
                <a href="{{ route('invoices.edit', ['invoice' => $invoice->id, 'company_uid' => $currentCompany->uid]) }}" class="btn btn-light">
                    <i class="material-icons">edit</i> 
                    {{ __('messages.edit') }}
                </a>
                <div class="btn-group">
                    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        {{ __('messages.more') }} <span class="caret"></span> 
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('customer_portal.invoices.details', ['invoice' => $invoice->uid, 'customer' => $invoice->customer->uid]) }}" class="dropdown-item" target="_blank">{{ __('messages.share') }}</a>
                        <a href="{{ route('invoices.mark', ['invoice' => $invoice->id, 'status' => 'paid', 'company_uid' => $currentCompany->uid]) }}" class="dropdown-item">{{ __('messages.mark_paid') }}</a>
                        <a href="{{ route('invoices.mark', ['invoice' => $invoice->id, 'status' => 'sent', 'company_uid' => $currentCompany->uid]) }}" class="dropdown-item">{{ __('messages.mark_sent') }}</a>
                        <hr>
                        <a href="{{ route('invoices.delete', ['invoice' => $invoice->id, 'company_uid' => $currentCompany->uid]) }}" class="dropdown-item text-danger delete-confirm">{{ __('messages.delete') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            @if ($invoice->is_recurring and $invoice->cycle != 0)
                <div class="alert alert-soft-success d-flex align-items-center" role="alert">
                    <i class="material-icons mr-3">schedule</i>
                    <div class="text-body">
                        {!! __('messages.recurring_alert', ['date' => $invoice->formatted_next_recurring_at]) !!} 
                    </div>
                </div>
            @endif
        </div>
        <div class="col-12">
            @if($invoice->status == 'DRAFT')
                <div class="alert alert-soft-dark d-flex align-items-center" role="alert">
                    <i class="material-icons mr-3">access_time</i>
                    <div class="text-body"><strong>{{ __('messages.status') }} : </strong> {{ __('messages.draft') }}</div>
                </div>
            @elseif($invoice->status == 'SENT')
                <div class="alert alert-soft-info d-flex align-items-center" role="alert">
                    <i class="material-icons mr-3">send</i>
                    <div class="text-body"><strong>{{ __('messages.status') }} : </strong> {{ __('messages.mailed_to_customer') }}</div>
                </div>
            @elseif($invoice->status == 'VIEWED')
                <div class="alert alert-soft-primary d-flex align-items-center" role="alert">
                    <i class="material-icons mr-3">visibility</i>
                    <div class="text-body"><strong>{{ __('messages.status') }} : </strong> {{ __('messages.viewed_by_customer') }}</div>
                </div>
            @elseif($invoice->status == 'OVERDUE')
                <div class="alert alert-soft-danger d-flex align-items-center" role="alert">
                    <i class="material-icons mr-3">schedule</i>
                    <div class="text-body"><strong>{{ __('messages.status') }} : </strong> {{ __('messages.overdue') }}</div>
                </div>
            @elseif($invoice->status == 'COMPLETED')
                <div class="alert alert-soft-success d-flex align-items-center" role="alert">
                    <i class="material-icons mr-3">done</i>
                    <div class="text-body"><strong>{{ __('messages.status') }} : </strong> {{ __('messages.payment_received') }}</div>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6 order-2 order-md-1">
            <div class="pdf-iframe">
                <iframe src="{{ route('pdf.invoice', $invoice->uid) }}" frameborder="0"></iframe>
            </div>
        </div>
        <div class="col-12 col-md-6 order-1 order-md-2">
            <nav class="nav nav-pills nav-justified w-100" role="tablist">
                <a href="#payments" class="h6 nav-item nav-link bg-secondary text-white active show" data-toggle="tab" role="tab" aria-selected="true">{{ __('messages.payments') }}</a>
                <a href="#activities" class="h6 nav-item nav-link bg-secondary text-white" data-toggle="tab" role="tab" aria-selected="false">{{ __('messages.activities') }}</a>
            </nav>
        
            <div class="tab-content">
                <div class="tab-pane active show" id="payments">
                    <div class="card">
                        <div class="mt-3 mb-3">
                            @include('application.payments._table')
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="activities">
                    <div class="card">
                        <div class="container-fluid page__container">
                            <p class="text-dark-gray d-flex align-items-center mt-3">
                                <i class="material-icons icon-muted mr-2">dvr</i>
                                <strong>{{ __('messages.activities') }}</strong>
                            </p>
                            @if($activities->count() > 0)
                                @foreach($activities as $activity)
                                    <div class="row align-items-center projects-item mb-1">
                                        <div class="col-sm-auto mb-1 mb-sm-0">
                                            <div class="text-dark-gray">{{ $activity->created_at->format($currentCompany->getSetting('date_format')) }}</div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="card m-0">
                                                <div class="px-4 py-3">
                                                    <div class="row align-items-center">
                                                        <div class="col mw-300px">
                                                            <div class="d-flex align-items-center">
                                                                <a class="text-body">
                                                                    @if($activity->description == 'viewed')
                                                                        <strong class="text-15pt mr-2">{{  __('messages.viewed_log', ['display_name' => $activity->causer->display_name]) }}</strong>
                                                                    @else
                                                                        <strong class="text-15pt mr-2">{{ $activity->description }}</strong>
                                                                    @endif
                                                                </a> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row align-items-center projects-item mb-1">
                                    <div class="col-sm-auto mb-1 mb-sm-0"></div>
                                    <div class="col-sm">
                                        <div class="card m-0">
                                            <div class="px-4 py-3">
                                                <div class="row align-items-center">
                                                    <div class="col mw-300px">
                                                        <div class="d-flex align-items-center">
                                                            <a class="text-body">
                                                                <strong class="text-15pt mr-2">{{ __('messages.no_activities_yet') }}</strong>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



