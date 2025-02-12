@extends('layouts.app', ['page' => 'credit_notes'])

@section('title', __('messages.update_credit_note'))
    
@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('credit_notes', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.credit_notes') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.update_credit_note') }}</li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.update_credit_note') }}</h1>
        </div>
    </div>
@endsection
 
@section('content') 
    <form action="{{ route('credit_notes.update', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]) }}" method="POST">
        @include('layouts._form_errors')
        @csrf
        
        @include('application.credit_notes._form')
    </form>
@endsection

@section('page_body_scripts')
    @include('application.credit_notes._js')
    <script>
        $(document).ready(function() {
            setupCustomer();

            $('tbody tr').each(function(index, element) {
                var row = $(element);

                // If the row is template just continue
                if(row.attr('id') === 'product_row_template') return;

                var productInput = row.find('[name="product[]"]');
                initializeProductSelect2(productInput);

                var taxInput = row.find('[name="taxes[]"]');
                initializeTaxSelect2(taxInput);
            });
            
            initializePriceListener();
            calculateRowPrice();
        });
    </script>
@endsection