<div class="row">
    <div class="col">
        <div class="form-group required">
            <label for="name">{{ __('messages.name') }}</label>
            <input name="name" type="text" class="form-control" placeholder="{{ __('messages.name') }}"
                value="{{ $product_brand->name }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="form-group select-container">
            <label for="categories">{{ __('messages.categories') }}</label>
            <select id="categories" name="categories[]" data-toggle="select" multiple="multiple"
                class="form-control select2-hidden-accessible select-with-footer" data-select2-id="categories">
                @foreach (get_product_categories_select2_array($currentCompany->id) as $option)
                    <option value="{{ $option['id'] }}"
                        {{ $product_brand->hasCategory($option['id']) ? 'selected=""' : '' }}>{{ $option['text'] }}
                    </option>
                @endforeach
            </select>
            <div class="d-none select-footer">
                <a href="{{ route('settings.product.category.create', ['company_uid' => $currentCompany->uid]) }}"
                    target="_blank" class="font-weight-300">+ {{ __('messages.add_new_category') }}</a>
            </div>
        </div>
    </div>
</div>
