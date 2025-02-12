<?php

namespace App\Http\Requests\Application\CreditNote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Store extends FormRequest
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
        if (is_array($this->product)) {
            // Make sure the lenght of product array is the same with other attributes of arrays
            $max_lenght = count($this->product);
            return [
                'credit_note_number' => ['required', Rule::unique('credit_notes')->where(function ($query) {
                    return $query->where('company_id', request()->user()->currentCompany()->id);
                })],
                'credit_note_date' => 'required|date',
                'reference_number' => 'nullable|string',
                'customer_id' => 'required|exists:customers,id',
                'sub_total' => 'required',
                'grand_total' => 'required',
                'notes' => 'nullable|string',
                'private_notes' => 'nullable|string',
                'template_id' => 'required|integer',

                'total_discount' => 'sometimes|integer|min:0',
                'total_taxes' => 'sometimes|array|min:0',

                'product' => 'required|array|max:'.$max_lenght,
                'product.*' => 'required',

                'quantity' => 'required|array|max:'.$max_lenght,
                'quantity.*' => 'required|integer|min:0',

                'price' => 'required|array|max:'.$max_lenght,
                'price.*' => 'required',

                'total' => 'required|array|max:'.$max_lenght,
                'total.*' => 'required',

                'taxes' => 'sometimes|required|array|max:'.$max_lenght,
                'taxes.*' => 'sometimes|required|array',

                'discount' => 'sometimes|required|array|max:'.$max_lenght,
                'discount.*' => 'sometimes|required',
            ];
        }

        return [
            'product' => 'required|array',
        ];
    }
 
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'credit_note_number.unique' => __('messages.credit_note_exists'),
            'product.required' => __('messages.please_select_a_product'),
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'credit_note_number' => $this->credit_note_prefix.'-'.sprintf('%06d', intval($this->credit_note_number)),
        ]);
    }
}