@extends('layouts.app', ['page' => 'credit_notes'])

@section('title', __('messages.create_credit_note'))
 
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('credit_notes', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.credit_notes') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.create_credit_note') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.create_credit_note') }}</h1>
        </div>
    </div>
@endsection
 
@section('content') 
    <form action="{{ route('credit_notes.store', ['company_uid' => $currentCompany->uid]) }}" method="POST">
        @include('layouts._form_errors')
        @csrf
        
        @include('application.credit_notes._form')
    </form>
@endsection

@section('page_body_scripts')
    @include('application.credit_notes._js')
    <script>
        $(document).ready(function() {
            addProductRow();
        });
    </script>
@endsection