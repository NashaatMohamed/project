<!-- Stylesheets -->
<link type="text/css" href="{{ asset('assets/vendor/simplebar.min.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/app.css?v=1.0.1') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-material-icons.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-fontawesome-free.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/vendor/select2/select2.min.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-flatpickr-airbnb.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/vendor-bootstrap-image-checkbox.css') }}" rel="stylesheet">

<!-- company based preferences -->
@shared
<!-- END company based preferences -->

<!-- page based scripts & styles -->
@yield('page_head_scripts')
<!-- END page based scripts & styles -->

@if (\Storage::disk('public_dir')->exists('/custom/custom.css'))
    <link type="text/css" href="{{ asset('uploads/custom/custom.css') }}" rel="stylesheet">
@endif

@if (\Storage::disk('public_dir')->exists('/custom/tracking.txt'))
    {!! Storage::disk('public_dir')->get('/custom/tracking.txt') !!}
@endif