@extends('layouts.app', ['page' => 'products'])

@section('title', __('messages.update_product'))

@section("css_custom")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <style>
        /* Your existing CSS styles */
    </style>
@endsection

@section("scripts")
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
@endsection

@section('page_header')
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{ route('products', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.products') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.update_product') }}</li>
                </ol>
            </nav>
            <h1 class="m-0 h3">{{ __('messages.update_product') }}</h1>
        </div>
        <a href="{{ route('products.delete', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}"
           class="btn btn-danger ml-3 delete-confirm">
            <i class="material-icons">delete</i>
            {{ __('messages.delete_product') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="col-lg-12 card-body">
        <!-- Dropzone and other existing content -->
    </div>

    <div class="col-lg-12">
        <form action="{{ route('products.update', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}"
              method="POST" enctype="multipart/form-data" files="true">
            @include('layouts._form_errors')
            @csrf
            @method('PUT')

            <div class="card card-form">
                <div class="row no-gutters">
                    <div class="col-lg-4 card-body">
                        <p><strong class="headings-color">{{ __('messages.product_information') }}</strong></p>
                        <p class="text-muted">{{ __('messages.basic_product_information') }}</p>
                    </div>
                    <div class="col-lg-8">
                        @include('application.products._form', ['product' => $product])
                    </div>
                </div>
            </div>

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
                                        <select id="variation_group_id" name="variation_group_id"
                                                data-toggle="select"
                                                class="form-control select2-hidden-accessible select-with-footer"
                                                data-select2-id="variation_group_id"
                                                data-minimum-results-for-search="-1">
                                            <!-- Add the "All Variation Group" option -->
                                            <option value="0" {{ $product->variation_group_id == null ? 'selected' : '' }}>{{ __('messages.all_variation_group') }}</option>
                                            @foreach(get_variation_groups_select2_array($currentCompany->id) as $key => $val)
                                                <option value="{{ $key }}" {{ $key == $product->variation_group_id ? "selected" : "" }}>{{ $val }}</option>
                                            @endforeach
                                        </select>
                                        <div class="d-none select-footer">
                                            <a href="{{ route('settings.variation_group.create', ['company_uid' => $currentCompany->uid]) }}"
                                               target="_blank"
                                               class="font-weight-300">+ {{ __('messages.add_new_variation_group') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <!-- Table for dynamic rows -->
                                    <div id="variation_table" class="table">
                                        <div class="mb-3" style="margin-left: 20px;">
                                            <input type="checkbox" id="toggleCheckbox" class="form-check-input"
                                                   checked>
                                            <span>{{ __("messages.enable_color") }}</span>
                                        </div>

                                        <div id="colors_id" class="mb-3">
                                            <select id="attributes_select_color_id" data-toggle="select"
                                                    name="colors[]" multiple
                                                    class="attributes_select form-control"
                                                    data-select2-id="attributes_select_color_id">
                                                @include("application.products.colors_options")
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
                <input type="hidden" name="product" value="{{ $product->id }}">
                <input type="hidden" name="variation_group_id" value="{{ $product->variation_group_id ?? 0 }}"
                       id="variation_group_id_hidden">

                <div class="card card-form">
                    <!-- Table for dynamic rows -->
                    <div style="overflow: auto !important;">
                        <div class="table-responsive">
                            <table class="table mb-0 thead-border-top-0 table-striped"
                                   id="product_variation_table"
                                   style="overflow: auto !important;">
                                <thead>
                                <tr id="thead_rows">
                                    <th>{{ __('messages.name') }}</th>
                                    <th>{{ __('messages.attributes') }}</th>
                                    <th id="thead_rows_th">{{ __('messages.price') }}</th>
                                    <th>{{ __('messages.quantity') }}</th>
                                    <th>SKU</th>
                                    <th></th> <!-- New column for delete button -->
                                </tr>
                                </thead>
                                <tbody id="product_variation_body">
                                @foreach($product->ProductVariations as $index => $variation)
                                    <tr id="product_variation_row_{{ $index }}">
                                        <td><span class="product_title">{{ $product->name }}</span></td>
                                        <td>
                                            <div class="form-group select-container">
                                                <label for="variation_select_{{ $index }}"></label>
                                                <select id="variation_select_{{ $index }}" name="variation_id[{{ $index }}][]"
                                                        data-toggle="select"
                                                        class="variation_select form-control select2-hidden-accessible select-with-footer"
                                                        data-select2-id="variation_select_{{ $index }}" multiple>
                                                    @foreach($variation->getVariationAttributes() as $attribute)
                                                        <option value="{{ $attribute->id }}" selected>{{ $attribute->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="d-none select-footer">
                                                    <a href="{{ route('settings.variation.create', ['company_uid' => $currentCompany->uid]) }}"
                                                       target="_blank"
                                                       class="font-weight-300">+ {{ __('messages.add_new_variation') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="body_rows_td">
                                            <input name="variation_price[{{ $index }}]" type="text" id="variation_price_{{ $index }}"
                                                   class="form-control price_input"
                                                   placeholder="{{ __('messages.price') }}"
                                                   autocomplete="off" value="{{ $variation->price }}">
                                        </td>
                                        <td>
                                            <input name="quantity[{{ $index }}]" type="number" class="form-control variation-stock" id="quantity_{{ $index }}"
                                                   placeholder="{{ __('messages.quantity') }}"
                                                   value="{{ $variation->quantity }}">
                                        </td>
                                        <td>
                                            <input name="sku[{{ $index }}]" type="text" class="form-control" id="sku_{{ $index }}"
                                                   placeholder="{{ __('messages.sku') }}"
                                                   value="{{ $variation->sku }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm delete-row">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div style="margin: 10px">
                            <button type="button" id="clone_row" class="btn btn-light"><i
                                        class="material-icons icon-16pt">add</i>{{ __('messages.add_variation') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group text-center mt-3">
                <button type="submit"
                        class="btn btn-primary form_with_price_input_submit">{{ __('messages.update_product') }}</button>
            </div>
        </form>
    </div>
@endsection

@section('page_body_scripts')

    <!-- stock synchronization -->
    <script>
        $(document).ready(function () {
            function calculateVariationStockSum() {
                let sum = 0;
                $('.variation-stock').each(function () {
                    sum += parseFloat($(this).val()) || 0;
                });
                return sum;
            }

            function calculateColorStockSum(variationRow) {
                let sum = 0;
                $(variationRow).find('.cloned_color_input').each(function () {
                    sum += parseFloat($(this).val()) || 0;
                });
                return sum;
            }

            function showSweetAlert(message, type = 'error') {
                Swal.fire({
                    icon: type,
                    title: 'Oops...',
                    text: message,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                });
            }

            $('body').on('input', '.variation-stock', function () {
                const mainStockValue = parseFloat($('input[name="opening_stock"]').val()) || 0;
                const variationStockSum = calculateVariationStockSum();

                if (variationStockSum > mainStockValue) {
                    showSweetAlert('{{ __("messages.variation_stock_exceeds_main_stock") }}');
                    $(this).val(mainStockValue - (variationStockSum - parseFloat($(this).val())));
                }
            });


            $('form').on('submit', function(e) {
                const mainStockValue = parseFloat($('input[name="opening_stock"]').val()) || 0;
                const variationStockSum = calculateVariationStockSum();

                if (variationStockSum !== mainStockValue) {
                    e.preventDefault();
                    showSweetAlert('{{ __("messages.variation_stock_mismatch") }}');
                    return;
                }

                const selectedColors = $('#attributes_select_color_id').val();

                if ($('#toggleCheckbox').is(':checked') && selectedColors.length > 0) {
                    let colorMismatchFound = false;

                    $('.variation-stock').each(function() {
                        const variationRow = $(this).closest('tr');
                        const variationStock = parseFloat($(this).val()) || 0;
                        const colorStockSum = calculateColorStockSum(variationRow);

                        if (colorStockSum !== variationStock) {
                            colorMismatchFound = true;
                            return false;
                        }
                    });

                    if (colorMismatchFound) {
                        e.preventDefault();
                        showSweetAlert('{{ __("messages.color_stock_mismatch") }}');
                    }
                }
            });

            $('#toggleCheckbox').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#colors_id').show();
                } else {
                    $('#colors_id').hide();
                }
            });
        });
    </script>


    <script>
        var attributesTree = [];
        var TaxAttributesTree = {!! json_encode(get_tax_types_select2_array($currentCompany->id)) !!};

        $(document).ready(function () {
            // Initialize Select2 for variation and tax dropdowns
            initializeSelect2();

            // Add the "All Variation Group" option if it doesn't exist
            var variationGroupDropdown = $("#variation_group_id");
            if (variationGroupDropdown.find('option[value="0"]').length === 0) {
                variationGroupDropdown.prepend('<option value="0">{{ __("messages.all_variation_group") }}</option>');
            }

            // Set the selected value based on the product's variation_group_id
            var variationGroupId = "{{ $product->variation_group_id }}";
            if (!variationGroupId) {
                variationGroupDropdown.val(0).trigger('change');
            } else {
                variationGroupDropdown.val(variationGroupId).trigger('change');
            }

            variationGroupDropdown.change(function () {
                var variation_group_id = $(this).val() || 0;
                $("#variation_group_id_hidden").val(variation_group_id);

                $.get("{{ route('ajax.get_variations_tree', ['company_uid' => $currentCompany->uid]) }}", {
                    variation_group_id: variation_group_id
                }, function (response) {
                    attributesTree = response;

                    initializeSelect2();
                });
            });

            variationGroupDropdown.trigger('change');

            // Handle cloning of rows
            $(document).on("click", "#clone_row", function () {
                var newRow = $('#product_variation_row').clone(true).show();
                newRow.removeAttr('id');
                newRow.attr('class', 'cloned');

                var rowsCount = $('.cloned').length;
                newRow.find(".variation_select, input, .vat").each(function () {
                    var oldName = $(this).attr('name');
                    var mergedName = oldName.replace(/\[\d+\]/, '[' + rowsCount + ']');
                    $(this).attr('name', mergedName);
                });

                newRow.appendTo($('#product_variation_body'));

                initializeSelect2(newRow);
            });

            function initializeSelect2(context) {
                var $context = context ? $(context) : $('body');

                $context.find('.variation_select').select2({
                    placeholder: '{{ __('messages.select_variation') }}',
                    multiple: true,
                    data: attributesTree,
                    width: 'resolve'
                });

                $context.find('.vat').select2({
                    placeholder: '{{ __('messages.select_taxes') }}',
                    multiple: true,
                    data: TaxAttributesTree,
                    width: 'resolve'
                });
            }
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
@endsection