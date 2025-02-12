<?php

namespace App\Http\Requests\Application\Settings\Preference;

use Illuminate\Foundation\Http\FormRequest;

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
            "currency_id" => 'required|integer',
            "language" => 'required|string|max:190',
            "timezone" => 'required|string|max:190',
            "date_format" => 'required|string|max:190',
            "financial_month_starts" => 'required|integer',
            "financial_month_ends" => 'required|integer',
            "discount_type" => 'required|boolean',
        ];
    }
}