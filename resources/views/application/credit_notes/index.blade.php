@extends('layouts.app', ['page' => 'credit_notes'])

@section('title', __('messages.credit_notes'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.credit_note') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.credit_notes') }}</h1>
        </div>
        <a href="{{ route('credit_notes.create', ['company_uid' => $currentCompany->uid]) }}" class="btn btn-success ml-3">
            <i class="material-icons">add</i> 
            {{ __('messages.create_credit_note') }}
        </a>
    </div>
@endsection

@section('content')
    @include('application.credit_notes._filters')

    <div class="card">
        @include('application.credit_notes._table')
    </div>
@endsection
