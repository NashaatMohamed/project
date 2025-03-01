<!-- resources/views/application/settings/variation/_form.blade.php -->

<div class="form-group">
    <label for="main_name">{{ __('messages.variation_name') }}</label>
    <input type="text" class="form-control" id="main_name" name="main_name"
           value="{{ old('main_name', $variation->name ?? '') }}"
           placeholder="{{ __('messages.enter_variation_name') }}" required>
</div>

<div class="form-group">

    <label for="variation_group">{{ __('messages.variation_group') }}</label>
    <select class="form-control select2" id="variation_group" name="variation_group_id" required>
        <option value="">{{ __('messages.select_variation_group') }}</option>
        @foreach($variationGroups as $group)
            <option value="{{ $group->id }}" {{ old('variation_group', $variation->variationGroup->first()->id ?? '') == $group->id ? 'selected' : '' }}>
                {{ $group->name }}
            </option>
        @endforeach
    </select>
</div>


<!-- أي حقول أخرى تتعلق بالتغيير يمكن إضافتها هنا -->
