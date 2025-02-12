<!-- resources/views/application/settings/variation/_form.blade.php -->

<div class="form-group">
    <label for="main_name">{{ __('messages.variation_name') }}</label>
    <input type="text" class="form-control" id="main_name" name="main_name" value="{{ old('main_name', $variation->name) }}" placeholder="{{ __('messages.enter_variation_name') }}" required>
</div>

<!-- أي حقول أخرى تتعلق بالتغيير يمكن إضافتها هنا -->
