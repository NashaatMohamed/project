@extends('layouts.app', ['page' => 'super_admin.pages'])

@section('title', __('messages.pages'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.pages') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.pages') }}</h1>
        </div>
        <a href="{{ route('super_admin.pages.create') }}" class="btn btn-success ml-3"><i class="material-icons">add</i> {{ __('messages.create_page') }}</a>
    </div>
@endsection

@section('content')
    <div class="card">
        @include('super_admin.pages._table')
    </div>
@endsection
