<!-- resources/views/application/settings/variation/edit.blade.php -->

@extends('layouts.app', ['page' => 'settings'])

@section('title', __('messages.edit_variation'))

@section('css_custom')
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: #f5f5f5;
        }

        .sortable-chosen {
            background: #f9f9f9;
        }

        .sortable-drag {
            background: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="page__heading">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-business-time icon-20pt"></i></a></li>
                <li class="breadcrumb-item">{{ __('messages.settings') }}</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('settings.variation', ['company_uid' => $currentCompany->uid]) }}">
                        {{ __('messages.variation') }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.edit_variation') }}</li>
            </ol>
        </nav>
        <h1 class="m-0">{{ __('messages.edit_variation') }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-3">
            @include('application.settings._aside', ['tab' => 'variation'])
        </div>
        <div class="col-lg-9">
            <form
                action="{{ route('settings.variation.update', ['variation' => $variation->id, 'company_uid' => $currentCompany->uid]) }}"
                method="POST">
                @include('layouts._form_errors')
                @csrf
                @method('PUT')

                <div class="card card-form">
                    <div class="card-body bg-white">
                        <div class="form-row align-items-center mb-4">
                            <div class="col">
                                <p class="h4 mb-0">
                                    <strong class="headings-color">{{ __('messages.edit_variation') }}</strong>
                                </p>
                            </div>
                        </div>

                        @include('application.settings.variation._form')
                    </div>
                </div>

                <div class="card card-form">
                    <div class="card-body bg-white">
                        <div class="form-row align-items-center mb-4">
                            <div class="col">
                                <p class="h4 mb-0">
                                    <strong class="headings-color">{{ __('messages.define_attributes') }}</strong>
                                </p>
                            </div>
                        </div>

                        @include('application.settings.variation.variations_form')
                    </div>
                </div>

                <div class="form-group text-right mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('messages.update_variation') }}</button>
                </div>

            </form>
        </div>
    </div>
@endsection
@section('page_body_scripts')
    <!-- تحميل مكتبة SortableJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {
            // تفعيل Select2 لعناصر معينة إذا كانت موجودة
            $('.select2').select2({
                tags: true,
                tokenSeparators: [',']
            });

            // إضافة صف جديد
            $('#add-row-btn').click(function() {
                var newRow = $('<tr>' +
                    '<td class="sortable-handle">' +
                    '<i class="fas fa-grip-vertical"></i>' +
                    '</td>' +
                    '<td><input type="text" class="form-control" name="name[]" placeholder="{{ __('messages.name') }}" required></td>' +
                    '<td><button type="button" class="btn btn-danger delete-row-btn">{{ __('messages.delete') }}</button></td>' +
                    '</tr>');
                $('.table-body').append(newRow);
            });

            // حذف الصف
            $(document).on('click', '.delete-row-btn', function() {
                $(this).closest('tr').remove();
            });

            // تفعيل الترتيب عبر SortableJS
            new Sortable(document.querySelector('.table-body'), {
                handle: '.sortable-handle', // المقبض لتفعيل السحب
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
            });
        });
    </script>
@endsection
