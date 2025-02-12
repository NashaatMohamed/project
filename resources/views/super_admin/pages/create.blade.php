@extends('layouts.app', ['page' => 'super_admin.pages'])

@section('title', __('messages.create_page'))
    
@section('page_head_scripts')
    <!-- Quill Theme -->
    <link type="text/css" href="{{ asset('assets/css/vendor-quill.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assets/css/vendor-quill.rtl.css') }}" rel="stylesheet">
@endsection

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('super_admin.pages') }}">{{ __('messages.pages') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.create_page') }}</li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.create_page') }}</h1>
        </div>
    </div>
@endsection
 
@section('content') 
    <form action="{{ route('super_admin.pages.store') }}" method="POST" enctype="multipart/form-data">
        @include('layouts._form_errors')
        @csrf
        
        @include('super_admin.pages._form')
    </form>
@endsection

@section('page_body_scripts') 
    <!-- Quill -->
    <script src="{{ asset('assets/vendor/quill.min.js') }}"></script>
    <script src="{{ asset('assets/js/quill.js') }}"></script>

    <script>
        $('.save_form_button').on('click', function() {
            var form = $(this).closest('form');
            var quill = $('.ql-editor').each(function (index, element) {
                var text_area = $(element).closest('.form-group').find('textarea');
                text_area.val($(element).html());
            });
            form.submit();
        });
    </script>
@endsection