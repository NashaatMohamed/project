@extends('layouts.app', ['page' => 'super_admin.settings.custom_css_js'])

@section('title', __('messages.custom_css_js'))

@section('page_head_scripts')
    <link href="{{ asset('assets/vendor/codemirror/lib/codemirror.css') }}" rel="stylesheet" />
@endsection

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a>{{ __('messages.custom_css_js') }}</a></li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.custom_css_js') }}</h1>
        </div>
    </div>
@endsection

@section('content') 
    <form action="{{ route('super_admin.settings.custom_css_js.update') }}" method="POST">
        @include('layouts._form_errors')
        @csrf

        <div class="card card-form">
            <div class="row no-gutters">
                <div class="col-lg-4 card-body">
                    <p><strong class="headings-color">{{ __('messages.custom_css_js') }}</strong></p>
                </div>
                <div class="col-lg-8 card-form__body card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('messages.custom_css') }}</label>
                                <textarea name="custom_css" id="custom_css">{!! $custom_css !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('messages.custom_js') }}</label>
                                <textarea name="custom_js" id="custom_js">{!! $custom_js !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('messages.tracking_code') }}</label> <br>
                                <small class="text-muted">{{ __('messages.tracking_code_description') }}</small>
                                <textarea name="tracking_code" id="tracking_code">{!! $tracking_code !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center mt-3">
                        <button class="btn btn-primary save_form_button">{{ __('messages.save_settings') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection  

@section('page_body_scripts')
    <script src="{{ asset('assets/vendor/codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('assets/vendor/codemirror/addon/edit/closebrackets.js') }}"></script>
    <script src="{{ asset('assets/vendor/codemirror/addon/edit/closetag.js') }}"></script>
    <script src="{{ asset('assets/vendor/codemirror/mode/javascript/javascript.js') }}"></script>
    <script src="{{ asset('assets/vendor/codemirror/mode/css/css.js') }}"></script>
    <script src="{{ asset('assets/vendor/codemirror/mode/xml/xml.js') }}"></script>
    <script>
        $( document ).ready(function() {
            var customCss = CodeMirror.fromTextArea(document.getElementById("custom_css"), {
                lineNumbers: true,
                mode: 'css',
                autoCloseBrackets: true,
                indentUnit: 4,
                indentWithTabs: true,
                tabSize: 4,
                lineWrapping: true,
            });
            var customJs = CodeMirror.fromTextArea(document.getElementById("custom_js"), {
                lineNumbers: true,
                mode: 'javascript',
                autoCloseBrackets: true,
                indentUnit: 4,
                indentWithTabs: true,
                tabSize: 4,
                lineWrapping: true,
            });
            var trackingCode = CodeMirror.fromTextArea(document.getElementById("tracking_code"), {
                lineNumbers: true,
                mode : "xml",
                htmlMode: true,
                autoCloseBrackets: true,
                autoCloseTags: true,
                indentUnit: 4,
                indentWithTabs: true,
                tabSize: 4,
                lineWrapping: true,
            });
        });
    </script>
@endsection