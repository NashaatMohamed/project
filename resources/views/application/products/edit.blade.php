@extends('layouts.app', ['page' => 'products'])

@section('title', __('messages.update_product'))

@section("css_custom")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>

    <style>
        .select2-selection__choice {
            float: left;
        }



        #product_variation_table input {
            width: 100px !important;
        }

        #product_variation_table input {
            width: 100px !important;
        }

        #product_variation_table .select2-container {
            min-width: 140px !important;
        }

        #product_variation_table .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
            display: flex;
        }

        .select2-container {
            min-width: 160px !important;
        }

        [dir=ltr] .select2-results__option {
            display: inline-block;
            width: 100%;
        }


        .product_title {
            width: 200px;
            display: inline-block;
        }

        .file_container {
            width: 350px !important;
        }


        .color-square,
        .color-square-selected {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 5px;
            border: 1px solid #999;
        }

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
                    <li class="breadcrumb-item" aria-current="page"><a
                                href="{{ route('products', ['company_uid' => $currentCompany->uid]) }}">{{ __('messages.products') }}</a>
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
        <div class="dropzone-container">
            <form id="myDropzone" action="{{ url('/upload') }}" method="post" enctype="multipart/form-data"
                  class="dropzone small-dropzone">
                @csrf
                <input class="dz-inpu" type="hidden" name="id" value="{{$product->id}}">
            </form>
        </div>


        @if($product->images)

            <script>
                Dropzone.options.myDropzone = {
                    addRemoveLinks: true,
                    thumbnailWidth: 300,
                    thumbnailHeight: 300,

                    init: function () {
                        var myDropzone = this;

                        // Load existing images into the Dropzone area
                        var existingImages = <?php echo json_encode(json_decode($product->images, true)); ?>;

                        existingImages.forEach(function (image) {
                            var mockFile = { name: image, size: 0 };
                            myDropzone.emit("addedfile", mockFile);
                            myDropzone.emit("thumbnail", mockFile, "{{ env("APP_URL") }}/uploads/product/{{$product->id}}/images/" + image);
                            myDropzone.emit("complete", mockFile);

                            // Bind the remove event to the remove link
                            mockFile.previewElement.querySelector(".dz-remove").addEventListener("click", function (e) {
                                e.preventDefault();
                                e.stopPropagation();

                                // Perform AJAX call to remove the image from the server
                                var imageFileName = image; // The image filename to be removed
                                var removeUrl = "remove-image"; // Replace with the actual URL for removing the image

                                // Send the AJAX request
                                fetch(removeUrl, {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({ filename: imageFileName, id: "{{$product->id}}" }),

                                })
                                    .then(function (response) {
                                        // Handle the response if needed
                                        return response.json();
                                    })
                                    .then(function (data) {
                                        if (data.success) {
                                            // On successful removal, remove the file from the Dropzone
                                            myDropzone.removeFile(mockFile);
                                        }
                                    })
                                    .catch(function (error) {
                                        console.error("Error:", error);
                                    });
                            });
                        });

                        myDropzone.on("success", function (file, response) {
                            if (response.success) {
                                // Trigger your custom event when an item is successfully uploaded
                                var customEvent = new Event("itemUploaded");
                                customEvent.filename = response.filename; // Assuming the response contains the filename of the uploaded image
                                customEvent.productID = "{{$product->id}}";
                                document.dispatchEvent(customEvent);

                                // Bind the remove event to the remove link for the newly added file
                                file.previewElement.querySelector(".dz-remove").addEventListener("click", function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();

                                    // Perform AJAX call to remove the image from the server for the newly added file
                                    var imageFileName = response.filename[0]; // Assuming the response contains the filename of the uploaded image
                                    var removeUrl = "remove-image"; // Replace with the actual URL for removing the image

                                    // Send the AJAX request
                                    fetch(removeUrl, {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                        },
                                        body: JSON.stringify({ filename: imageFileName, id: "{{$product->id}}" }),

                                    })
                                        .then(function (response) {
                                            // Handle the response if needed
                                            return response.json();
                                        })
                                        .then(function (data) {
                                            if (data.success) {
                                                // On successful removal, remove the file from the Dropzone
                                                myDropzone.removeFile(file);
                                            }
                                        })
                                        .catch(function (error) {
                                            console.error("Error:", error);
                                        });
                                });
                            }
                        });
                    },
                };

            </script>


        @endif
    </div>

    <div class="col-lg-12">
        <form action="{{ route('products.update', ['product' => $product->id, 'company_uid' => $currentCompany->uid]) }}"
              method="POST" enctype="multipart/form-data" files="true">
            @include('layouts._form_errors')
            @csrf

            <div class="card card-form">
                <div class="row no-gutters">
                    <div class="col-lg-4 card-body">
                        <p><strong class="headings-color">{{ __('messages.product_information') }}</strong></p>
                        <p class="text-muted">{{ __('messages.basic_product_information') }}</p>
                    </div>
                    <div class="col-lg-8">

                        @include('application.products._form')

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
                                            <option disabled
                                                    selected>{{ __('messages.select_variation_group') }}</option>
                                            @foreach($x=get_variation_groups_select2_array($currentCompany->id) as $key=>$val)
                                                <option value="{{$key }}" {{$key==$product->variation_group_id?"selected":""}}>{{ $val }}</option>
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
                                                    name="attributes_select_color_id" multiple
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
                <input type="hidden" name="product" value="{{$product->id}}">
                <input type="hidden" name="variation_group_id" value="{{$product->variation_group_id}}"
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
                                    <th>{{__("messages.name")}}</th>
                                    <th>{{__("messages.attributes")}}</th>
                                    <th id="thead_rows_th">{{__("messages.price")}}</th>
                                    <th>{{__("messages.tax")}}</th>
                                    {{--                                <th>{{__("messages.image")}}</th>--}}
                                    <th>{{__("messages.quantity")}}</th>
                                    <th>SKU</th>
                                    <th></th> <!-- New column for delete button -->
                                </tr>
                                </thead>
                                <tbody id="product_variation_body">
                                <tr id="product_variation_row">
                                    <td><span class="product_title">{{$product->name}}</span></td>
                                    <td>
                                        <div class="form-group select-container ">
                                            <label for="variation_select"></label>
                                            <select id="variation_select" name="variation_id"
                                                    data-toggle="select"
                                                    class="variation_select form-control select2-hidden-accessible select-with-footer"
                                                    data-select2-id="variation_select"></select>
                                            <div class="d-none select-footer">
                                                <a href="{{ route('settings.variation.create', ['company_uid' => $currentCompany->uid]) }}"
                                                   target="_blank"
                                                   class="font-weight-300">+ {{ __('messages.add_new_variation') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="body_rows_td"><input name="price" type="text" id="price"
                                                                    class="form-control price_input"
                                                                    placeholder="{{ __('messages.price') }}"
                                                                    autocomplete="off" value="0"></td>
                                    <td>
                                        <div class="form-group select-container ">
                                            <label for="vat"></label>
                                            <select id="vat" name="vat"
                                                    data-toggle="select"
                                                    class="vat form-control select2-hidden-accessible select-with-footer"
                                                    data-select2-id="vat">
                                            </select>
                                            <div class="d-none select-footer">
                                                <a href="{{ route('settings.tax_types.create', ['company_uid' => $currentCompany->uid]) }}"
                                                   target="_blank"
                                                   class="font-weight-300">+ {{ __('messages.add_new_tax') }}</a>
                                            </div>
                                        </div>

                                    </td>

                                    <td><input name="quantity" type="number" class="form-control" id="quantity"
                                               placeholder="{{ __('messages.quantity') }}"
                                               value="{{ $product->quantity }}"></td>
                                    <td><input name="sku" type="text" class="form-control" id="sku"
                                               placeholder="{{ __('messages.sku') }}"
                                               value="{{ $product->sku }}"></td>
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
                                        class="material-icons icon-16pt">add</i>{{__("messages.add_variation")}}
                            </button>
                        </div>
                    </div>

                </div>


            </div>

            <div class="form-group text-center mt-3">
                <button type="button"
                        class="btn btn-primary form_with_price_input_submit">{{ __('messages.save_product') }}</button>
            </div>
        </form>
    </div>

@endsection

@section('page_body_scripts')

    {{--Init--}}
    <script>
        var attributesTree = [];
        var TaxAttributesTree = {!! get_tax_types_select2_array($currentCompany->id) !!};

        $(document).ready(function () {
            $('.variation_select').select2();
            $('.vat').select2();
        });
    </script>

    {{--Group on change--}}
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function () {

            $("#variation_group_id").change(function () {

                var variation_group_id = $("#variation_group_id").val();
                $("#variation_group_id_hidden").val(variation_group_id);

                $.get("<?php echo e(route('ajax.get_variations_tree', ['company_uid' => $currentCompany->uid])); ?>", {variation_group_id: variation_group_id}, function (response) {

                    attributesTree = response;

                    $('.variation_select:not(:first),.vat:not(:first)').each(function () {
                        if ($(this).data('select2')) {
                            $(this).select2('destroy');
                        }
                    });

                    $('.variation_select:not(:first)').empty().select2({
                        placeholder: '', // Remove the "undefined" placeholder
                        multiple: true, // Enable multi-select
                        data: attributesTree,

                        width: 'resolve' // need to override the changed default
                    });


                    $('.vat:not(:first)').empty().select2({
                        placeholder: '', // Remove the "undefined" placeholder
                        multiple: true, // Enable multi-select

                        width: 'resolve', // need to override the changed default
                        data: TaxAttributesTree
                    });
                });
            });
        });
    </script>

    {{--Colors--}}
    <script type="text/javascript">
        $(document).ready(function () {
            $("#product_variation_btn").hide();
            $("#product_variation_row").hide();

            // Handle click event on the checkbox
            $('#toggleCheckbox').on('change', function () {
                if ($(this).is(':checked')) {

                    $('#colors_id').show();
                } else {
                    $('#colors_id').hide();
                }
                draw_product_variation_table();
            });

            $('#attributes_select_color_id').select2({
                multiple: true, // Enable multi-select,
                width: 'resolve', // need to override the changed default
                placeholder: "<?php echo e(__("messages.select_option")); ?>",
                templateResult: function (option) {
                    if (!option.id) {
                        return option.text;
                    }

                    var color = option.id.toLowerCase();
                    return $('<span><span class="color-square" style="background-color:' + color + '"></span>' + option.text + '</span>');
                },
                templateSelection: function (option) {
                    if (!option.id) {
                        return option.text;
                    }

                    var color = option.id.toLowerCase();
                    return $('<span><span class="color-square-selected" style="background-color:' + color + '"></span>' + option.text + '</span>');
                }
            });

            $('#attributes_select_color_id').on("change", function () {
                draw_product_variation_table();
            })

        });
    </script>

    {{--Draw Row--}}
    <script>


        $(document).ready(function () {

            $(document).on("click", ".delete-row", function () {
                $('.variation_select:not(:first),.vat:not(:first)').each(function () {
                    if ($(this).data('select2'))
                        $(this).select2('destroy');
                });

                $('.variation_select:not(:first),.vat:not(:first)').select2();

                $(this).parents("tr:first").remove();

            });


            $(document).on("click", "#clone_row", function () {

                $('.variation_select,.vat').each(function () {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                });


                if ($("#variation_group_id").val()) {
                    var rowsCount = $('.cloned').length;

                    var newRow = $('#product_variation_row').clone(true).show(); // Clone the table
                    newRow.appendTo($('#product_variation_body')); // Append the cloned row to the table

                    // row
                    newRow.removeAttr('id'); // Remove the ID attribute from the cloned row
                    newRow.attr('class', 'cloned'); // Remove the ID attribute from the cloned row


                    newRow.find("#variation_select").attr("data-select2-id", "variation_select" + rowsCount);
                    newRow.find("#vat").attr("data-select2-id", "vat" + rowsCount);

                    newRow.find(".variation_select,input,.vat").each(function () {
                        var oldName = $(this).attr('name'); // Step 1
                        // Merge the old name with the new name and variation ID
                        var mergedName = oldName + '[' + rowsCount + '][]'; // Step 2
                        // Set the merged name as the new value for the name attribute
                        $(this).attr('name', mergedName); // Step 3
                    });

                    $('.variation_select:not(:first)').select2({
                        placeholder: '', // Remove the "undefined" placeholder
                        multiple: true, // Enable multi-select
                        data: attributesTree,

                        width: 'resolve' // need to override the changed default
                    });

                    // Reset the cloned row's select value
                    newRow.find('.variation_select').val('').trigger('change');

                    // var tax_select = newRow.find('[name="vat"]');
                    {{--tax_select.select2({  placeholder: "{{ __('messages.select_taxes') }}"});--}}
                    $('.vat:not(:first)').select2({
                        placeholder: '', // Remove the "undefined" placeholder
                        multiple: true, // Enable multi-select

                        data: TaxAttributesTree,
                        width: 'resolve' // need to override the changed default

                    });
                    // Reset the cloned row's select value
                    newRow.find('.vat').val('').trigger('change');


                }
            });


        })

        function draw_product_variation_table() {

            $(".clonedTh").remove();
            if ($("#colors_id").css('display') !== 'none') {
                // JavaScript code to get the values of the selected options and insert them into table header cells
                const selectedOptionValues = $("#attributes_select_color_id option:selected").map(function () {
                    return $(this).text(); // or $(this).val() if you want to get the option values instead of text
                }).get();

                const $tableHeaderRow = $("#thead_rows_th");

                selectedOptionValues.forEach(function (value) {
                    $("<th class='clonedTh'>" + value + "</th>").insertBefore($tableHeaderRow);
                });
            }

            $(".clonedTd").remove();
            if ($("#colors_id").css('display') !== 'none') {
                ($("#attributes_select_color_id").val()).forEach(function (i, data) {
                    $color_input = '<input name="colors_quantity[' + i + ']"  type="number" class="form-control cloned_color_input" placeholder="{{ __('messages.quantity') }}"  ></td>';

                    $("<td class='clonedTd'>" + $color_input + "  </td>").insertBefore($(".body_rows_td"));
                });
            }


        }

    </script>

    {{--Draw Old Variations--}}
    <script>
        function draw_old_variations() {
            @php
                $productVariations= $product->ProductVariations()->get(["variations_json"]);
            @endphp

            $('.variation_select,.vat').each(function () {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });

            var variation_group_id = $("#variation_group_id").val();

            $.get("<?php echo e(route('ajax.get_variations_tree', ['company_uid' => $currentCompany->uid])); ?>", {variation_group_id: variation_group_id}, function (response) {
                if (!jQuery.isEmptyObject(response)) {
                    attributesTree = response;
                    if (attributesTree.length > 0)
                        $("#product_variation_btn").show();
                    else
                        $("#product_variation_btn").hide();


                    @foreach($productVariations as $index=>$variation_record)



                    var jsonData = JSON.parse({!! $variation_record !!}["variations_json"]);
                    var keys = Object.keys(jsonData.price);

                    console.log(keys);
                    var rowsCount = "{{$index}}";
                    var newRow = $('#product_variation_row').clone(true).show(); // Clone the table row
                    newRow.removeAttr('id'); // Remove the ID attribute from the cloned row
                    newRow.attr('class', 'cloned'); // Remove the ID attribute from the cloned row

                    newRow.find("#variation_select").attr("data-select2-id", "variation_select" + rowsCount)
                    newRow.find("#vat").attr("data-select2-id", "vat" + rowsCount)


                    $select2VariationInput = newRow.find('.variation_select').select2({
                        placeholder: '', // Remove the "undefined" placeholder
                        multiple: true, // Enable multi-select
                        data: attributesTree,

                        width: 'resolve' // need to override the changed default
                    });
                    $select2VariationInput.select2();

                    $select2VatInput = newRow.find('.vat').select2({
                        placeholder: '', // Remove the "undefined" placeholder
                        multiple: true, // Enable multi-select

                        width: 'resolve', // need to override the changed default
                        data: TaxAttributesTree
                    });
                    $select2VatInput.select2();


                    if (jsonData.price && jsonData.price[keys[{{$index}}]] != null)
                        newRow.find('input[name="price"]').val(jsonData.price[keys[{{$index}}]]); // Clear the input and select values in the cloned row

                    if (jsonData.vat && jsonData.vat[keys[{{$index}}]] != null) {
                        $select2VatInput.val(jsonData.vat[keys[{{$index}}]]).trigger('change');
                    }
                    if (jsonData.quantity && jsonData.quantity[keys[{{$index}}]] != null)
                        newRow.find('input[name="quantity"]').val(jsonData.quantity[keys[{{$index}}]]); // Clear the input and select values in the cloned row

                    if (jsonData.sku && jsonData.sku[keys[{{$index}}]] != null)
                        newRow.find('input[name="sku"]').val(jsonData.sku[keys[{{$index}}]]); // Clear the input and select values in the cloned row
                    {{--if (jsonData.cover && jsonData.cover[keys[{{$index}}]] != null)--}}
                            {{--    newRow.find('input[name="cover"]').val(jsonData.cover[keys[{{$index}}]]); // Clear the input and select values in the cloned row--}}
                    if (jsonData.variation_id && jsonData.variation_id[keys[{{$index}}]] != null) {
                        $select2VariationInput.val(jsonData.variation_id[keys[{{$index}}]]).trigger('change'); // Clear the input and select values in the cloned row

                    }

                    ($("#attributes_select_color_id").val()).forEach(function (data) {
                        if (jsonData.colors_quantity && jsonData.colors_quantity[data][keys[{{$index}}]] != null)
                            newRow.find('input[name="colors_quantity[' + data + ']"]').val(jsonData.colors_quantity[data][keys[{{$index}}]]); // Clear the input and select values in the cloned row
                        // colors_quantity[Red][0]

                    });

                    newRow.find(".variation_select,input,.vat").each(function () {
                        var oldName = $(this).attr('name');  // Step 1
                        // Merge the old name with the new name and variation ID
                        var mergedName = oldName + '[' + rowsCount + '][]';  // Step 2
                        // Set the merged name as the new value for the name attribute
                        $(this).attr('name', mergedName);  // Step 3
                    })

                    newRow.appendTo($('#product_variation_body')); // Append the cloned row to the table

                    @endforeach

                }
            });


            // header
            {{--variations = {!! json_encode(json_decode($productVariations[0]->variations_json)->variations) !!};--}}

            {{--(variations).forEach(function (data) {--}}
            {{--    $("<th class='clonedTh' data='" + data + "' >" + data + "</th>").insertBefore($("#thead_rows_th"));--}}
            {{--});--}}
        }


        $(document).ready(function () {
            draw_old_variations();
            draw_product_variation_table();

            if (!"{{$product->variation_group_id}}") {
                $('#variation_group_id').val($('#variation_group_id option:eq(1)').val()).trigger('change');
                $("#clone_row").click();
            }

        });
    </script>


    <script>
        $(function () {
            $("#pNameId").on("input", function () {
                $(".product_title").text($(this).val());
            })

            $("#product_variation_table").on("input", ".cloned_color_input", function () {
                let sum = 0;
                const $tr = $(this).closest("tr"); // Use closest() to find the closest ancestor <tr>

                $tr.find(".cloned_color_input").each(function () {
                    const value = parseFloat($(this).val());
                    sum += isNaN(value) ? 0 : value;
                });

                $tr.find("#quantity").val(sum);
            });

        })
    </script>

@endsection

