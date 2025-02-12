<?php

namespace App\Http\Requests\Application\Settings\Account;

use App\Http\Requests\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */ 
    public function rules()
    {
        return [
            'avatar' => 'mimes:jpeg,jpg,png,gif',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'full_phone' => ['required', 'string', ''/*,'regex:/[0-9]{11}/'*//*,Rule::unique('users,phone')->ignore(request()->user()->id),*/],
            'old_password' => ['sometimes', 'nullable', 'string', 'min:8', new MatchOldPassword],
            'new_password' => 'required_with:old_password|nullable|string|min:8',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users')->ignore(request()->user()->id),
            ],
        ];
    }
}