@extends('layouts.app', ['page' => 'super_admin.banks'])

@section('title', __('messages.create_bank'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('super_admin.banks') }}">{{ __('messages.banks') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.create_bank') }}</li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.create_bank') }}</h1>
        </div>
    </div>
@endsection
 
@section('content') 
    <form action="{{ route('super_admin.banks.store') }}" method="POST" enctype="multipart/form-data">
        @include('layouts._form_errors')
        @csrf
        
        @include('super_admin.banks._form')
    </form>
@endsection