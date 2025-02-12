@extends('layouts.customer_portal', ['page' => 'credit_notes'])

@section('title', __('messages.credit_note_details'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ __('messages.portal') }}</li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.credit_notes') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.credit_notes') }}</h1>
        </div>
    </div>
@endsection
 
@section('content')

    @include('customer_portal.credit_notes._filters')

    <div class="card">
        @include('customer_portal.credit_notes._table')
    </div>
@endsection
