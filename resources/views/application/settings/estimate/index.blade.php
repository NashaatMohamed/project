@extends('layouts.app', ['page' => 'settings'])

@section('title', __('messages.estimate_settings'))
    
@section('page_head_scripts')
<style>
    #myImg {
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
    }
    
    #myImg:hover {opacity: 0.7;}
    
    /* The Modal (background) */
    .modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1; /* Sit on top */
      padding-top: 100px; /* Location of the box */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
    }
    
    /* Modal Content (image) */
    .modal-content {
      margin: auto;
      display: block;
      width: 80%;
      max-width: 700px;
    }
    
    /* Caption of Modal Image */
    #caption {
      margin: auto;
      display: block;
      width: 80%;
      max-width: 700px;
      text-align: center;
      color: #ccc;
      padding: 10px 0;
      height: 150px;
    }
    
    /* Add Animation */
    .modal-content, #caption {  
      -webkit-animation-name: zoom;
      -webkit-animation-duration: 0.6s;
      animation-name: zoom;
      animation-duration: 0.6s;
    }
    
    @-webkit-keyframes zoom {
      from {-webkit-transform:scale(0)} 
      to {-webkit-transform:scale(1)}
    }
    
    @keyframes zoom {
      from {transform:scale(0)} 
      to {transform:scale(1)}
    }
    
    /* The Close Button */
    .close {
      position: absolute;
      top: 15px;
      right: 35px;
      color: #f1f1f1;
      font-size: 40px;
      font-weight: bold;
      transition: 0.3s;
    }
    
    .close:hover,
    .close:focus {
      color: #bbb;
      text-decoration: none;
      cursor: pointer;
    }
    
    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
      .modal-content {
        width: 100%;
      }
    }
</style>
<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/vendor-quill.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-quill.rtl.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="page__heading">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">business</i></a></li>
                <li class="breadcrumb-item">{{ __('messages.settings') }}</li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.estimate_settings') }}</li>
            </ol>
        </nav>
        <h1 class="m-0">{{ __('messages.estimate_settings') }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-3">
            @include('application.settings._aside', ['tab' => 'estimate'])
        </div>
        <div class="col-lg-9">
            
            <div class="card card-form">
                <div class="row no-gutters">
                    <div class="col card-form__body card-body bg-white">
                        <form action="{{ route('settings.estimate.update', ['company_uid' => $currentCompany->uid]) }}" method="POST">
                            @include('layouts._form_errors')
                            @csrf

                            <div class="form-group mb-4">
                                <p class="h5 mb-0">
                                    <strong class="headings-color">{{ __('messages.estimate_settings') }}</strong>
                                </p>
                                <p class="text-muted">{{ __('messages.customize_estimate_settings') }}</p>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <div class="form-group required">
                                        <label for="estimate_prefix">{{ __('messages.estimate_prefix') }}</label>
                                        <input name="estimate_prefix" type="text" class="form-control" value="{{ $currentCompany->getSetting('estimate_prefix') }}" placeholder="{{ __('messages.estimate_prefix') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="estimate_auto_archive">{{ __('messages.auto_archive') }}</label><br>
                                        <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                            <input type="checkbox" name="estimate_auto_archive" id="estimate_auto_archive" {{ $currentCompany->getSetting('estimate_auto_archive') ? 'checked' : '' }} class="custom-control-input">
                                            <label class="custom-control-label" for="estimate_auto_archive">{{ __('messages.yes') }}</label>
                                        </div>
                                        <label for="estimate_auto_archive" class="mb-0">{{ __('messages.yes') }}</label>
                                        <small class="form-text text-muted">
                                            {{ __('messages.auto_archive_description_estimate') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-5">
                                    <div class="form-group required">
                                        <label for="estimate_color">{{ __('messages.estimate_color') }}</label>
                                        <input name="estimate_color" type="color" class="form-control" value="{{ $currentCompany->getSetting('estimate_color') }}" placeholder="{{ __('messages.estimate_color') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="estimate_auto_convert">{{ __('messages.auto_convert') }}</label><br>
                                        <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                            <input type="checkbox" name="estimate_auto_convert" id="estimate_auto_convert" {{ $currentCompany->getSetting('estimate_auto_convert') ? 'checked' : '' }} class="custom-control-input">
                                            <label class="custom-control-label" for="estimate_auto_convert">{{ __('messages.yes') }}</label>
                                        </div>
                                        <label for="estimate_auto_convert" class="mb-0">{{ __('messages.yes') }}</label>
                                        <small class="form-text text-muted">
                                            {{ __('messages.auto_convert_description') }}
                                        </small>
                                    </div>
                                </div>
                            </div> 

                            <div class="form-group">
                                <label class="h5" for="estimate_footer">{{ __('messages.footer') }}</label>
                                <div class="quill h-250px" data-toggle="quill" data-quill-placeholder="{{ __('messages.content') }}" data-quill-modules-toolbar='[["bold", "italic", "underline"]]'>
                                    {!! $currentCompany->getSetting('estimate_footer') !!}
                                </div>
                                <textarea name="estimate_footer" class="d-none" required>{!! $currentCompany->getSetting('estimate_footer') !!}</textarea>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="h5">{{ __('messages.company_address_format') }}</label>
                                        <p class="text-muted"><a href="#" data-toggle="modal" data-target="#modal-field-tags">{{ __('messages.show_templates') }}</a></p>
                                        <div class="quill h-250px" data-toggle="quill" data-quill-placeholder="{{ __('messages.content') }}" data-quill-modules-toolbar='[["bold", "italic", "underline"]]'>
                                            {!! get_company_setting('estimate_from_template', $currentCompany->id) !!}
                                        </div>
                                        <textarea name="estimate_from_template" class="d-none" required>{!! get_company_setting('estimate_from_template', $currentCompany->id) !!}</textarea>
                                    </div>
                                </div>
    
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="h5">{{ __('messages.customer_billing_address_format') }}</label>
                                        <p class="text-muted"><a href="#" data-toggle="modal" data-target="#modal-field-tags">{{ __('messages.show_templates') }}</a></p>
                                        <div class="quill h-250px" data-toggle="quill" data-quill-placeholder="{{ __('messages.content') }}" data-quill-modules-toolbar='[["bold", "italic", "underline"]]'>
                                            {!! get_company_setting('estimate_to_template', $currentCompany->id) !!}
                                        </div>
                                        <textarea name="estimate_to_template" class="d-none" required>{!! get_company_setting('estimate_to_template', $currentCompany->id) !!}</textarea>
                                    </div>
                                </div>
    
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="h5">{{ __('messages.customer_shipping_address_format') }}</label>
                                        <p class="text-muted"><a href="#" data-toggle="modal" data-target="#modal-field-tags">{{ __('messages.show_templates') }}</a></p>
                                        <div class="quill h-250px" data-toggle="quill" data-quill-placeholder="{{ __('messages.content') }}" data-quill-modules-toolbar='[["bold", "italic", "underline"]]'>
                                            {!! get_company_setting('estimate_ships_to_template', $currentCompany->id) !!}
                                        </div>
                                        <textarea name="estimate_ships_to_template" class="d-none" required>{!! get_company_setting('estimate_ships_to_template', $currentCompany->id) !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="estimate_template">{{ __('messages.estimate_template') }}</label>
                                @php $default_template = get_company_setting('estimate_template', $currentCompany->id) @endphp
                                <div class="row mt-3">
                                    @foreach(\App\Models\EstimateTemplate::all() as $template)
                                        <div class="col-md-3">
                                            <div class="custom-control custom-radio image-checkbox">
                                                <input type="radio" class="custom-control-input" id="{{$template->view}}" name="estimate_template" value="{{$template->view}}" @if($default_template === $template->view) checked='' @endif>
                                                <label class="custom-control-label" for="{{$template->view}}">
                                                    <img src="{{ $template->path }}" class="img-fluid modal-image">
                                                    <span>{{ $template->name }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
            
                            <div class="form-group text-right mt-4">
                                <button type="submit" class="btn btn-primary save_form_button">{{ __('messages.update_settings') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@section('page_body_scripts')
    @include('application.settings._pdf_field_tags_modal')

    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" id="modalImage" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.modal-image').on('click', function () {
            $('#modalImage').attr('src', $(this).attr('src'))
            $('#imagemodal').modal('show');
        });
    </script>

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

