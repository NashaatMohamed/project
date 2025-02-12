<?php

// app/Http/Requests/Application/Settings/Variation/Store.php

namespace App\Http\Requests\Application\Settings\Variation;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    /**
     * تحديد ما إذا كان المستخدم مخولًا بإجراء هذا الطلب.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * الحصول على قواعد التحقق التي تنطبق على الطلب.
     *
     * @return array
     */ 
    public function rules()
    {
        return [
            'main_name' => 'required|string|max:190',
            'name' => 'required|array|min:1',
            'name.*' => 'required|string|max:190',
        ];
    }
}