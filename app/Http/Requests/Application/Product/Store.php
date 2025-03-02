<?php

namespace App\Http\Requests\Application\Product;

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


    public function rules()
    {
        return [
            // الحقول الأساسية للمنتج
            'name' => 'required|string|max:190',
            'unit_id' => 'required|integer|exists:product_units,id',
            'price' => 'nullable',
            'description' => 'nullable|string|max:500',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity_alarm' => 'nullable|numeric',
            'opening_stock' => 'nullable|numeric',
            'category_id' => 'nullable|integer|exists:product_categories,id',
            'brand_id' => 'nullable|integer|exists:product_brands,id',
            'warehouse_id' => 'nullable|integer|exists:warehouses,id',
            'code' => 'nullable|string|max:127',
            'barcode' => 'nullable|string|max:127',
//            'hide' => 'nullable|boolean',
            'variation_group_id' => 'nullable',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string|max:190',

            "colors_quantity" => 'nullable|array',
            "colors_quantity.*" => 'nullable|array',
            "colors_quantity.*.*" => 'nullable|numeric',

            'variation_price' => 'nullable|array',
            'variation_price.*' => 'nullable|numeric',

            'variation_id' => 'nullable|array',
            'variation_id.*' => 'nullable',
//
            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable',
//
            'sku' => 'nullable|array',
            'sku.*' => 'nullable|string|max:127',
//
//            'vat' => 'nullable|array',
//            'vat.*' => 'nullable|numeric',
//
//            // الحقول الإضافية للتغييرات
//            'discount' => 'nullable|array',
//            'discount.*' => 'nullable|numeric',
//
//            'image' => 'nullable|array',
//            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//
//
//            'variation_id' => 'nullable|array',
//            'variation_id.*' => 'nullable|array',
//            'variation_id.*.*' => [
//                'nullable',
//                'integer',
//                Rule::exists('variations', 'id')->where(function ($query) use ($currentCompany) {
//                    $query->where('company_id', $currentCompany->id);
//                }),
//            ],
        ];
    }

    public function prepareForValidation()
    {
//        $this->merge([
//            "price" => ltrim(preg_replace('/[^0-9\.]/', '', $this->price), '.'),
//        ]);
    }


}
