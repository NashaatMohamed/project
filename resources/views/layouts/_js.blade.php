<!-- Application Scripts -->
<script src="{{ asset('assets/vendor/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/popper.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/vendor/dom-factory.js') }}"></script>
<script src="{{ asset('assets/vendor/material-design-kit.js') }}"></script>
<script src="{{ asset('assets/js/toggle-check-all.js') }}"></script>
<script src="{{ asset('assets/js/check-selected-row.js') }}"></script>
<script src="{{ asset('assets/js/dropdown.js') }}"></script>
<script src="{{ asset('assets/js/sidebar-mini.js') }}"></script>
<script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
<script src="{{ asset('assets/vendor/flatpickr/flatpickr.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/custom.js?v=1.0.3') }}"></script>

@if (\Storage::disk('public_dir')->exists('/custom/custom.js'))
    <script src="{{ asset('uploads/custom/custom.js') }}"></script>
@endif

<script>
    $(document).ready(function(){
        // Sweet alert delete confirmation
        $('.delete-confirm').on('click', function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            Swal.fire({
                title: "{{ __('messages.are_you_sure') }}",
                text: "{{ __('messages.this_record_will_be_deleted') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d', 
                confirmButtonText: 'Delete!',
                focusConfirm: false,
                focusCancel: false,
            }).then((result) => {
                if (result.value) {
                    window.location.href = url;
                }
            })
        });
    });
</script>

@yield('page_body_scripts')

