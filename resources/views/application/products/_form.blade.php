
<div class="card-form__body card-body">



    <div class="row">
 
        <div class="col">
            <div class="form-group select-container required">
                <label for="warehouse_id">{{ __('messages.warehouse') }}</label>
                <select id="warehouse_id" name="warehouse_id" data-toggle="select"
                        class="form-control select2-hidden-accessible select-with-footer"
                        data-select2-id="warehouse_id" data-minimum-results-for-search="-1">
                    <option disabled selected>{{ __('messages.select_warehouse') }}</option>
                    @foreach( $data=get_product_warehouses_select2_array($currentCompany->id) as $option)
                        <option value="{{ $option['id'] }}" {{( $product->warehouse_id == $option['id'] || count($data)==1 ) ? 'selected=""' : '' }}>{{ $option['text']}}</option>
                    @endforeach
                </select>
                <div class="d-none select-footer">
                    <a href="{{ route('settings.warehouse.create', ['company_uid' => $currentCompany->uid]) }}"
                       target="_blank" class="font-weight-300">+ {{ __('messages.add_new_warehouse') }}</a>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col">
            <div class="form-group required">
                <label for="name">{{ __('messages.name') }}</label>
                <input name="name" id="pNameId"  type="text" class="form-control" placeholder="{{ __('messages.name') }}"
                       value="{{ $product->name }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-group select-container required">
                <label for="unit">{{ __('messages.unit') }}</label>
                <select id="unit_id" name="unit_id" data-toggle="select"
                        class="form-control select2-hidden-accessible select-with-footer"
                        data-select2-id="unit_id" data-minimum-results-for-search="-1">
                    <option disabled selected>{{ __('messages.select_unit') }}</option>
                    @foreach(get_product_units_select2_array($currentCompany->id) as $option)
                        <option value="{{ $option['id'] }}" {{ $product->unit_id == $option['id'] ? 'selected=""' : '' }}>{{ $option['text'] }}</option>
                    @endforeach
                </select>
                <div class="d-none select-footer">
                    <a href="{{ route('settings.product.unit.create', ['company_uid' => $currentCompany->uid]) }}"
                       target="_blank" class="font-weight-300">+ {{ __('messages.add_new_product_unit') }}</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group required">
                <label for="price">{{ __('messages.price') }}</label>
                <input name="main_price" type="text" class="form-control price_input"
                       placeholder="{{ __('messages.price') }}" autocomplete="off"
                       value="{{ $product->price ?? 0 }}" required>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col">
            <div class="form-group select-container">
                <label for="taxes">{{ __('messages.taxes') }}</label>
                <select id="taxes" name="taxes[]" data-toggle="select" multiple="multiple"
                        class="form-control select2-hidden-accessible select-with-footer"
                        data-select2-id="taxes">
                    @foreach(get_tax_types_select2_array($currentCompany->id) as $option)
                        <option value="{{ $option['id'] }}" {{ $product->hasTax($option['id']) ? 'selected=""' : '' }}>{{ $option['text'] }}</option>
                    @endforeach
                </select>
                <div class="d-none select-footer">
                    <a href="{{ route('settings.tax_types.create', ['company_uid' => $currentCompany->uid]) }}"
                       target="_blank" class="font-weight-300">+ {{ __('messages.add_new_tax') }}</a>
                </div>
            </div>
        </div>
    </div>


    <div class="row">

        <div class="col">
            <div class="form-group ">
                <label for="opening_stock">{{ __('messages.opening_stock') }}</label>
                <input name="opening_stock" type="text" class="form-control  "
                       placeholder="{{ __('messages.opening_stock') }}" autocomplete="off"
                       value="{{ $product->opening_stock  }}" required>
            </div>
        </div>

        <div class="col">
            <div class="form-group ">
                <label for="quantity_alarm">{{ __('messages.quantity_alarm') }}</label>
                <input name="quantity_alarm" type="number" class="form-control"
                       placeholder="{{ __('messages.quantity_alarm') }}" value="{{ $product->quantity_alarm }}"
                       required>
            </div>
        </div>
    </div>


    <div class="row">

        <div class="col">
            <div class="form-group select-container ">
                <label for="brand_id">{{ __('messages.brand') }}</label>
                <select id="brand_id" name="brand_id" data-toggle="select"
                        class="form-control select2-hidden-accessible select-with-footer"
                        data-select2-id="brand_id" data-minimum-results-for-search="-1">
                    <option disabled selected>{{ __('messages.select_brand') }}</option>
                    @foreach(get_product_brands_select2_array($currentCompany->id) as $option)
                        <option value="{{ $option['id'] }}" {{ $product->brand_id == $option['id'] ? 'selected=""' : '' }}>{{ $option['text'] }}</option>
                    @endforeach
                </select>
                <div class="d-none select-footer">
                    <a href="{{ route('settings.product.brand.create', ['company_uid' => $currentCompany->uid]) }}"
                       target="_blank" class="font-weight-300">+ {{ __('messages.add_new_brand') }}</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="form-group select-container">
                <label for="category_id">{{ __('messages.category') }}</label>
                <select id="category_id" name="category_id" data-toggle="select"
                        class="form-control select2-hidden-accessible select-with-footer"
                        data-select2-id="category_id" data-minimum-results-for-search="-1">
                    <option disabled selected>{{ __('messages.select_category') }}</option>
                    @foreach(get_product_categories_select2_array($currentCompany->id,$product->brand_id) as $option)
                        <option value="{{ $option['id'] }}" {{ $product->category_id == $option['id'] ? 'selected=""' : '' }}>{{ $option['text'] }}</option>
                    @endforeach
                </select>
                <div class="d-none select-footer">
                    <a href="{{ route('settings.product.category.create', ['company_uid' => $currentCompany->uid]) }}"
                       target="_blank" class="font-weight-300">+ {{ __('messages.add_new_category') }}</a>
                </div>
            </div>
        </div>



    </div>


    <div class="row">

        <div class="col">
            <div class="form-group  ">
                <label for="code">{{ __('messages.item_code') }}</label>
                <input name="code" type="text" class="form-control  "
                       placeholder="{{ __('messages.item_code') }}" autocomplete="off"
                       value="{{ $product->code}}" >
            </div>
        </div>

        <div class="col">
            <div class="form-group  ">
                <label for="barcode">{{ __('messages.barcode') }}</label>
                <input name="barcode" type="text" class="form-control  "
                       placeholder="{{ __('messages.barcode') }}" autocomplete="off"
                       value="{{ $product->barcode}}" >
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="description">{{ __('messages.description') }}</label>
                <textarea name="description" class="form-control" cols="30"
                          rows="3">{{ $product->description }}</textarea>
            </div>
        </div>
    </div>

@if($product->getCustomFields()->count() > 0)
        <div class="row">
            @foreach ($product->getCustomFields() as $custom_field)
                <div class="col">
                    @include('layouts._custom_field', ['model' => $product, 'custom_field' => $custom_field])
                </div>
            @endforeach
        </div>
    @endif


</div>

@section("scripts")
    <script type="text/javascript">
        function readURL(input, image_index) {
            console.log(image_index)
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.' + image_index).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $("#brand_id").change(function() {
            var brand_id = $("#brand_id").val();

            $.get("{{ route('ajax.categories', ['company_uid' => $currentCompany->uid]) }}", {brand_id: brand_id}, function(response) {
                if(!jQuery.isEmptyObject(response)) {
                    $('#category_id').empty();
                    $('#category_id').select2({
                        placeholder: 'Select Category',
                        minimumResultsForSearch: -1,
                        data: response,
                        templateSelection: function (data, container) {
                            return data.text;
                        }
                    });
                }else{
                    $('#category_id').empty();

                }
            });
        });
    </script>

@endsection
