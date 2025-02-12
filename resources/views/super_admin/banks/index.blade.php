@extends('layouts.app', ['page' => 'super_admin.banks'])

@section('title', __('messages.banks'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.banks') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.banks') }}</h1>
        </div>
        <a href="{{ route('super_admin.banks.create') }}" class="btn btn-success ml-3"><i class="material-icons">add</i> {{ __('messages.create_bank') }}</a>
    </div>
@endsection

@section('content')
    <div class="card">
        @include('super_admin.banks._table')
    </div>
@endsection
