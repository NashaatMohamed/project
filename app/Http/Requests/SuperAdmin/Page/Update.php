<?php

namespace App\Http\Requests\SuperAdmin\Page;

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
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:32768',
            'content' => 'nullable|string|max:32768',
            'slug' => 'nullable|string|max:32768|unique:pages,slug,'.$this->page,
            'is_active' => 'sometimes|boolean',
            'order' => 'integer'
        ];
    }
}