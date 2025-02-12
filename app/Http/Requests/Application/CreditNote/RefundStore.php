<?php

namespace App\Http\Requests\Application\CreditNote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RefundStore extends FormRequest
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
            'refund_date' => 'required|date',
            'payment_method_id' => 'required|integer',
            'amount' => 'required',
            'notes' => 'nullable|string',
        ];
    }
}