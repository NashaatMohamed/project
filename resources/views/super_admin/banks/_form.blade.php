<div class="card card-form">
    <div class="row no-gutters">
        <div class="col-lg-4 card-body">
            <p><strong class="headings-color">{{ __('messages.bank_information') }}</strong></p>
        </div>
        <div class="col-lg-8 card-form__body card-body">

            <div class="form-group">
                <label>{{ __('messages.logo') }}</label><br>
                <input type="file" onchange="changePreview(this);" class="d-none" name="logo" id="logo">
                <label for="logo">
                    <div class="media align-items-center">
                        <div class="mr-3">
                            <div class="avatar avatar-xl">
                                <img id="file-prev" src="{{ $bank->avatar }}" class="avatar-img rounded">
                            </div>
                        </div>
                        <div class="media-body">
                            <a class="btn btn-sm btn-light choose-button">{{ __('messages.choose_file') }}</a>
                        </div>
                    </div>
                </label><br>
            </div>
            
            <div class="row">
                <div class="col"> 
                    <div class="form-group required">
                        <label for="name">{{ __('messages.name') }}</label>
                        <input name="name" type="text"  class="form-control input" placeholder="{{ __('messages.name') }}" value="{{ $bank->name }}" required>
                    </div>
                </div>
            </div>
 
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="description">{{ __('messages.description') }}</label>
                        <textarea name="description" class="form-control" cols="30" rows="3" placeholder="{{ __('messages.description') }}">{{ $bank->description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-group text-center mt-3">
                <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            </div>
        </div>
    </div>
</div>
