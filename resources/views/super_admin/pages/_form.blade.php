<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.page_information') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group required">
                        <label for="name">{{ __('messages.name') }}</label>
                        <input name="name" type="text" class="form-control" placeholder="{{ __('messages.name') }}" value="{{ $page->name }}" required>
                    </div>
                </div> 
                <div class="col-12">
                    <div class="form-group">
                        <label for="description">{{ __('messages.description') }}</label>
                        <textarea name="description" type="text" class="form-control" placeholder="{{ __('messages.description') }}">{{ $page->description }}</textarea>
                    </div>
                </div>
                @if ($page->slug and $page->is_deletable)
                    <div class="col-12">
                        <div class="form-group">
                            <label for="slug">{{ __('messages.slug') }}</label>
                            <input name="slug" type="text" class="form-control" placeholder="{{ __('messages.slug') }}" value="{{ $page->slug }}" required>
                        </div>
                    </div>
                @endif
            </div> 

            <div class="row">
                <div class="col-12">
                    <div class="form-group required">
                        <label>{{ __('messages.content') }}</label>
                        <div class="quill h-250px" data-toggle="quill" data-quill-placeholder="{{ __('messages.content') }}" data-quill-modules-toolbar='[["bold", "italic", "underline"], ["link", "blockquote"], [{"list": "ordered"}, {"list": "bullet"}]]'>
                            {!! $page->content !!}
                        </div>
                        <textarea name="content" class="d-none" required>{!! $page->content !!}</textarea>
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col">
                    <div class="form-group required">
                        <label for="order">{{ __('messages.order') }}</label>
                        <input name="order" type="number" class="form-control" placeholder="{{ __('messages.order') }}" value="{{ $page->order }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="is_active">{{ __('messages.active') }}</label>
                        <select name="is_active" class="form-control">
                            <option value="0" {{ $page->is_active == false ? 'selected' : '' }}>{{ __('messages.disabled') }}</option>
                            <option value="1" {{ $page->is_active == true  ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group text-center mt-5">
                <button class="btn btn-primary save_form_button">{{ __('messages.save_page') }}</button>
            </div>
        </div>
    </div>
</div>