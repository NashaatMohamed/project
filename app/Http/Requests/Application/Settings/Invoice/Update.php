<?php

namespace App\Http\Requests\Application\Settings\Invoice;

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
            'invoice_prefix' => 'required|string|max:190',
            'invoice_color' => 'required|string|max:190',
            'invoice_footer' => 'nullable|string',
            'invoice_due_reminder_1_before_days' => 'nullable',
            'invoice_due_reminder_2_before_days' => 'nullable',
            'invoice_overdue_reminder_1_after_days' => 'nullable',
            'invoice_overdue_reminder_2_after_days' => 'nullable',
            'invoice_show_payments_on_pdf' => 'required|boolean',
            'invoice_auto_archive' => 'required|boolean',
            'invoice_template' => 'required|string',
            'invoice_from_template' => 'required|string',
            'invoice_to_template' => 'required|string',
            'invoice_ships_to_template' => 'required|string',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        foreach ($this->rules() as $key => $value) {
            // Set values of checked checkboxes from 'on' to true
            if ($this->$key == 'on') {
                $this->merge([
                    $key => 1,
                ]);
            }

            // Add unposted checkbox values as false
            if (!$this->has($key)) {
                $this->merge([
                    $key => 0,
                ]);
            }
        }
    }
}