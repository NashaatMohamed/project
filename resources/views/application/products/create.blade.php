@extends('layouts.app', ['page' => 'products'])

@section('title', __('messages.create_product'))

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a
                            href="{{ route('products', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.products') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.create_product') }}</li>
                </ol>
            </nav>
            <h1 class="m-0">{{ __('messages.create_product') }}</h1>
        </div>
    </div>
@endsection

@section('css_custom')
    <!-- تضمين ملفات CSS المطلوبة -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- تضمين ملفات JavaScript المطلوبة -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

    <!-- أنماط CSS المخصصة -->
    <style>
        /* ... جميع أنماط CSS الخاصة بك ... */
        /* سأترك الأنماط كما هي لأنها صحيحة. */
    </style>
@endsection

@section('content')
    <form action="{{ route('products.store', ['company_uid' => $currentCompany->uid]) }}" method="POST"
        enctype="multipart/form-data">
        @include('layouts._form_errors')
        @csrf

        @include('application.products.create_form')

        <!-- قسم التغييرات والجدول -->

        <div class="form-group d-flex justify-content-end mt-3">
            <input type="checkbox" id="toggleVariations" class="form-check-input">
            <label for="toggleVariations" class="ml-2">{{ __('messages.enable_variations') }}</label>
        </div>
        <div id="variationsSection" style="display: none;">
        <div class="card card-form">
            <div class="row no-gutters">

                <div class="col-lg-4 card-body">

                    <p><strong class="headings-color">{{ __('messages.product_variations') }}</strong></p>

                </div>

                <div class="col-lg-8">
                    <div class="card-form__body card-body">
                        <div class="row">

                            <div class="col">
                                <div class="form-group select-container required">
                                    <label for="variation_group_id">{{ __('messages.variation_group') }}</label>
                                    <select id="variation_group_id" name="variation_group_id" class="form-control select2">
                                        <option disabled selected>{{ __('messages.select_variation_group') }}</option>
                                        @foreach (get_variation_groups_select2_array($currentCompany->id) as $key => $val)
                                            <option value="{{ $key }}"
                                                {{ old('variation_group_id') == $key ? 'selected' : '' }}>
                                                {{ $val }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="d-none select-footer">
                                        <a href="{{ route('settings.variation_group.create', ['company_uid' => $currentCompany->uid]) }}"
                                            target="_blank" class="font-weight-300">+
                                            {{ __('messages.add_new_variation_group') }}</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col">
                                <!-- Table for dynamic rows -->
                                <div id="variation_table" class="table">
                                    <div class="mb-3" style="margin-left: 20px;">
                                        <input type="checkbox" id="toggleCheckbox" class="form-check-input" checked>
                                        <span>{{ __('messages.enable_color') }}</span>
                                    </div>

                                    <div id="colors_id" class="mb-3">
                                        <select id="attributes_select_color_id" data-toggle="select"
                                            name="colors[]" multiple
                                            class="attributes_select form-control"
                                            data-select2-id="attributes_select_color_id">
                                            @include('application.products.colors_options')
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="text-center">
            <input type="hidden" name="product" value="{{ $product->id ?? '' }}">
            <input type="hidden" name="variation_group_id" value="{{ old('variation_group_id') }}"
                id="variation_group_id_hidden">

            <div class="card card-form">
                <div style="overflow: auto !important;">
                    <div class="table-responsive">
                        <table class="table mb-0 thead-border-top-0 table-striped" id="product_variation_table"
                            style="overflow: auto !important;">
                            <thead>
                                <tr id="thead_rows">
                                    <th>{{ __('messages.name') }}</th>
                                    <th>{{ __('messages.attributes') }}</th>
                                    <th id="thead_rows_th">{{ __('messages.price') }}</th>
                                    <th>{{ __('messages.tax') }}</th>
                                    <!-- <th>{{ __('messages.image') }}</th> -->
                                    <th>{{ __('messages.quantity') }}</th>
                                    <th>SKU</th>
                                    <th></th> <!-- New column for delete button -->
                                </tr>
                            </thead>
                            <tbody id="product_variation_body">
                                <tr id="product_variation_row">
                                    <td><span class="product_title"> - </span></td>
                                    <td>
                                        <div class="form-group select-container">
                                            <label for="variation_select"></label>
                                            <select id="variation_select" name="variation_id[0][]" data-toggle="select"
                                                class="variation_select form-control select2 select-with-footer"
                                                multiple data-select2-id="variation_select">
                                            </select>
                                            <div class="d-none select-footer">
                                                <a href="{{ route('settings.variation.create', ['company_uid' => $currentCompany->uid]) }}"
                                                    target="_blank" class="font-weight-300">+
                                                    {{ __('messages.add_new_variation') }}</a>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="body_rows_td">

                                        <input type="text" name="price[]" id="price" value=""
                                            class="form-control" placeholder="{{ __('messages.price') }}">

                                    </td>



                                    <td>
                                        <div class="form-group select-container">
                                            <label for="vat"></label>
                                            <select id="vat" name="vat[0][]" data-toggle="select"
                                                class="vat form-control select2 select-with-footer"
                                                multiple data-select2-id="vat">
                                            </select>
                                            <div class="d-none select-footer">
                                                <a href="{{ route('settings.tax_types.create', ['company_uid' => $currentCompany->uid]) }}"
                                                    target="_blank" class="font-weight-300">+
                                                    {{ __('messages.add_new_tax') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input name="quantity[]" type="number" class="form-control" id="quantity"
                                            placeholder="{{ __('messages.quantity') }}"
                                            value="">
                                    </td>
                                    <td>
                                        <input name="sku[]" type="text" class="form-control" id="sku"
                                            placeholder="{{ __('messages.sku') }}" value="">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm delete-row">Delete
                                        </button>
                                    </td> <!-- Delete button for the row -->
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <div style="margin: 10px">
                        <button type="button" id="clone_row" class="btn btn-light"><i
                                class="material-icons icon-16pt">add</i>{{ __('messages.add_variation') }}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

        <div class="form-group text-center mt-3">
            <button type="submit" class="btn btn-primary">{{ __('messages.save_product') }}</button>
        </div>
    </form>

@endsection

@section('page_body_scripts')

    <!-- تهيئة المتغيرات والمكتبات -->
    <script>
        // تحويل بيانات الضرائب إلى JSON لاستخدامها في JavaScript
        var TaxAttributesTree = {!! json_encode(get_tax_types_select2_array($currentCompany->id)) !!};

        // تعريف متغيرات جافاسكريبت
        var attributesTree = [];

        $(document).ready(function() {
            // تهيئة Select2 للحقول
            $('.variation_select').select2();
            $('.vat').select2();
        });
    </script>

    <!-- عند تغيير مجموعة التغييرات -->
    <script>
        $(document).ready(function() {

            $("#variation_group_id").change(function() {

                var variation_group_id = $("#variation_group_id").val();
                $("#variation_group_id_hidden").val(variation_group_id);

                $.get("{{ route('ajax.get_variations_tree', ['company_uid' => $currentCompany->uid]) }}", {
                    variation_group_id: variation_group_id
                }, function(response) {

                    attributesTree = response;

                    $('.variation_select:not(:first), .vat:not(:first)').each(function() {
                        if ($(this).data('select2')) {
                            $(this).select2('destroy');
                        }
                    });

                    $('.variation_select:not(:first)').empty().select2({
                        placeholder: '{{ __('messages.select_variation') }}',
                        multiple: true,
                        data: attributesTree,
                        width: 'resolve'
                    });

                    $('.vat:not(:first)').empty().select2({
                        placeholder: '{{ __('messages.select_taxes') }}',
                        multiple: true,
                        data: TaxAttributesTree,
                        width: 'resolve'
                    });
                });
            });
        });
    </script>

    <!-- التعامل مع الألوان -->
    <script>
        $(document).ready(function() {
            $("#product_variation_btn").hide();
            $("#product_variation_row").hide();

            // حدث عند تغيير حالة checkbox
            $('#toggleCheckbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#colors_id').show();
                } else {
                    $('#colors_id').hide();
                }
                draw_product_variation_table();
            });

            // تهيئة Select2 للألوان
            $('#attributes_select_color_id').select2({
                multiple: true,
                width: 'resolve',
                placeholder: "{{ __('messages.select_option') }}",
                templateResult: function(option) {
                    if (!option.id) {
                        return option.text;
                    }
                    var color = option.id.toLowerCase();
                    return $('<span><span class="color-square" style="background-color:' + color +
                        '"></span>' + option.text + '</span>');
                },
                templateSelection: function(option) {
                    if (!option.id) {
                        return option.text;
                    }
                    var color = option.id.toLowerCase();
                    return $('<span><span class="color-square-selected" style="background-color:' +
                        color + '"></span>' + option.text + '</span>');
                }
            });

            $('#attributes_select_color_id').on("change", function() {
                draw_product_variation_table();
            });

        });
    </script>

    <!-- رسم الصفوف -->
    <script>
        $(document).ready(function() {

            // حذف الصف عند النقر على زر الحذف
            $(document).on("click", ".delete-row", function() {
                $(this).closest("tr").remove();
            });

            // إضافة صف جديد عند النقر على زر "إضافة تغيير"
            $(document).on("click", "#clone_row", function() {

                $('.variation_select, .vat').each(function() {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                });

                if ($("#variation_group_id").val()) {
                    var rowsCount = $('.cloned').length;

                    var newRow = $('#product_variation_row').clone(true).show();
                    newRow.appendTo($('#product_variation_body'));

                    newRow.removeAttr('id');
                    newRow.attr('class', 'cloned');

                    newRow.find("#variation_select").attr("data-select2-id", "variation_select" +
                    rowsCount);
                    newRow.find("#vat").attr("data-select2-id", "vat" + rowsCount);

                    newRow.find(".variation_select, input, .vat").each(function() {
                        var oldName = $(this).attr('name');
                        var mergedName = oldName.replace(/\[\d*\]/, '[' + rowsCount + ']');
                        $(this).attr('name', mergedName);
                    });

                    $('.variation_select:not(:first)').select2({
                        placeholder: '{{ __('messages.select_variation') }}',
                        multiple: true,
                        data: attributesTree,
                        width: 'resolve'
                    });

                    newRow.find('.variation_select').val('').trigger('change');

                    $('.vat:not(:first)').select2({
                        placeholder: '{{ __('messages.select_taxes') }}',
                        multiple: true,
                        data: TaxAttributesTree,
                        width: 'resolve'
                    });

                    newRow.find('.vat').val('').trigger('change');
                }
            });

        });

        // دالة لرسم جدول التغييرات بناءً على الألوان المختارة
        function draw_product_variation_table() {

            $(".clonedTh").remove();
            if ($("#colors_id").css('display') !== 'none') {
                const selectedOptionValues = $("#attributes_select_color_id option:selected").map(function() {
                    return $(this).text();
                }).get();

                const $tableHeaderRow = $("#thead_rows_th");

                selectedOptionValues.forEach(function(value) {
                    $("<th class='clonedTh'>" + value + "</th>").insertBefore($tableHeaderRow);
                });
            }

            $(".clonedTd").remove();
            if ($("#colors_id").css('display') !== 'none') {
                ($("#attributes_select_color_id").val()).forEach(function(data) {
                    var color_input = '<input name="colors_quantity[' + data +
                        '][]" type="number" class="form-control cloned_color_input" placeholder="{{ __('messages.quantity') }}" />';
                    $("<td class='clonedTd'>" + color_input + "</td>").insertBefore($(".body_rows_td"));
                });
            }
        }
    </script>

    <!-- سكربتات إضافية -->
    <script>
        $(function() {
            // تحديث عنوان المنتج عند تغيير الاسم
            $("#pNameId").on("input", function() {
                $(".product_title").text($(this).val());
            });

            // حساب مجموع الكميات للألوان
            $("#product_variation_table").on("input", ".cloned_color_input", function() {
                let sum = 0;
                const $tr = $(this).closest("tr");

                $tr.find(".cloned_color_input").each(function() {
                    const value = parseFloat($(this).val());
                    sum += isNaN(value) ? 0 : value;
                });

                $tr.find("input[name='quantity[]']").val(sum);
            });

        });
    </script>

{{--    // إظهار أو إخفاء القسم عند تغيير حالة الـ Checkbox--}}
    <script>
        $(document).ready(function() {
            $('#toggleVariations').change(function() {
                if ($(this).is(':checked')) {
                    $('#variationsSection').slideDown();
                } else {
                    $('#variationsSection').slideUp();
                }
            });
        });
    </script>

@endsection
