<?php

namespace App\Http\Controllers\Application\products;

use App\Http\Controllers\Application\ProductVariation;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Product\Store;
use App\Http\Requests\Application\Product\Update;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OldProductController extends Controller
{
    /**
     * Display Products Page
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();
 
        // Get Products by Company
        $products = QueryBuilder::for(Product::findByCompany($currentCompany->id))
            ->where('hide', false)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('unit_id'),
            ])
            ->oldest()
            ->paginate()
            ->appends(request()->query());

        return view('application.products.index', [
            'products' => $products
        ]);
    }

  
    // public function create(Request $request)
    // {
    //     $product = new Product();

    //     // Fill model with old input
    //     if (!empty($request->old())) {
    //         $product->fill($request->old());
    //     }

    //     return view('application.products.create', [
    //         'product' => $product,
    //     ]); 
    // }



public function create(Request $request)
{
    $product = new Product();

    // ملء الحقول الفردية فقط لتجنب ملء مصفوفات
    $product->name = old('name');
    $product->price = old('main_price');
    $product->description = old('description');
    $product->unit_id = old('unit_id');
    $product->warehouse_id = old('warehouse_id');
    $product->opening_stock = old('opening_stock');
    $product->quantity_alarm = old('quantity_alarm');
    $product->brand_id = old('brand_id');
    $product->category_id = old('category_id');
    $product->code = old('code');
    $product->barcode = old('barcode');
    // أضف الحقول الأخرى حسب الحاجة

    return view('application.products.create', [
        'product' => $product,
    ]);
}


    
    
    // public function store(Store $request)
    // {
    //     $user = $request->user();
    //     $currentCompany = $user->currentCompany();

    //     // Redirect back
    //     $canAdd = $currentCompany->subscription('main')->canUseFeature('products');
    //     if (!$canAdd) {
    //         session()->flash('alert-danger', __('messages.you_have_reached_the_limit'));
    //         return redirect()->route('products', ['company_uid' => $currentCompany->uid]);
    //     }

    //     // Create Product and Store in Database
    //     $product = Product::create([
    //         'name' => $request->name,
    //         'company_id' => $currentCompany->id,
    //         'unit_id' => $request->unit_id,
    //         'price'  => $request->price,
    //         'description' => $request->description,
    //     ]);

    //     // Add custom field values
    //     $product->addCustomFields($request->custom_fields);

    //     // Add Product Taxes
    //     if ($request->has('taxes')) {
    //         foreach ($request->taxes as $tax) {
    //             $product->taxes()->create([
    //                 'tax_type_id' => $tax
    //             ]);
    //         }
    //     }

    //     // Record product 
    //     $currentCompany->subscription('main')->recordFeatureUsage('products');

    //     session()->flash('alert-success', __('messages.product_added'));
    //     return redirect()->route('products', ['company_uid' => $currentCompany->uid]);
    // }


// public function store(Store $request)
// {
//     $user = $request->user();
//     $currentCompany = $user->currentCompany();

//     // التحقق من إمكانية إضافة منتج جديد
//     $canAdd = $currentCompany->subscription('main')->canUseFeature('products');
//     if (!$canAdd) {
//         session()->flash('alert-danger', __('messages.you_have_reached_the_limit'));
//         return redirect()->route('products', ['company_uid' => $currentCompany->uid]);
//     }

//     // معالجة صورة الغلاف الرئيسية (إن وجدت)
//     $coverImagePath = null;
//     if ($request->hasFile('cover')) {
//         $coverImage = $request->file('cover');
//         $coverImageName = time() . '_' . $coverImage->getClientOriginalName();
//         $coverImage->storeAs('uploads/product/', $coverImageName, 'public');
//         $coverImagePath = $coverImageName;
//     }

//     // معالجة الصور الإضافية (إن وجدت)
//     $imagesPaths = [];
//     if ($request->hasFile('images')) {
//         foreach ($request->file('images') as $image) {
//             $imageName = time() . '_' . $image->getClientOriginalName();
//             $image->storeAs('uploads/product/', $imageName, 'public');
//             $imagesPaths[] = $imageName;
//         }
//     }

//     // إنشاء المنتج الأساسي وتخزينه في قاعدة البيانات
//     $product = Product::create([
//         'name' => $request->name,
//         'company_id' => $currentCompany->id,
//         'unit_id' => $request->unit_id,
//         'price'  => $request->price, // سعر المنتج الأساسي
//         'description' => $request->description,
//         'currency_id' => $request->currency_id,
//         'cover' => $coverImagePath,
//         'images' => !empty($imagesPaths) ? json_encode($imagesPaths) : null,
//         'quantity_alarm' => $request->quantity_alarm,
//         'opening_stock' => $request->opening_stock,
//         'category_id' => $request->category_id,
//         'brand_id' => $request->brand_id,
//         'warehouse_id' => $request->warehouse_id,
//         'code' => $request->code,
//         'barcode' => $request->barcode,
//         'hide' => $request->hide,
//         'variation_group_id' => $request->variation_group_id,
//         'colors' => $request->colors ? json_encode($request->colors) : null,
//     ]);

//     // إضافة الحقول المخصصة
//     if ($request->has('custom_fields')) {
//         $product->addCustomFields($request->custom_fields);
//     }

//     // إضافة ضرائب المنتج الأساسي
//     if ($request->has('taxes')) {
//         foreach ($request->taxes as $tax) {
//             $product->taxes()->create([
//                 'tax_type_id' => $tax
//             ]);
//         }
//     }

//     // معالجة تغييرات المنتج
//     if ($request->has('price')) {
//         $prices = $request->input('price', []);
//         $variationIds = $request->input('variation_id', []);
//         $quantities = $request->input('quantity', []);
//         $skus = $request->input('sku', []);
//         $vats = $request->input('vat', []);
//         $discounts = $request->input('discount', []);
//         $variationImages = $request->file('image', []);

//         foreach ($prices as $index => $variationPrice) {
//             // معالجة صورة التغيير (إن وجدت)
//             $variationImagePath = null;
//             if (isset($variationImages[$index]) && $variationImages[$index]->isValid()) {
//                 $variationImage = $variationImages[$index];
//                 $variationImageName = time() . '_' . $variationImage->getClientOriginalName();
//                 $variationImage->storeAs('uploads/product/', $variationImageName, 'public');
//                 $variationImagePath = $variationImageName;
//             }

//             $variationData = [
//                 'product_id' => $product->id,
//                 'price' => $variationPrice,
//                 'variation_id' => isset($variationIds[$index]) ? $variationIds[$index] : null,
//                 'quantity' => isset($quantities[$index]) ? $quantities[$index] : 0,
//                 'sku' => isset($skus[$index]) ? $skus[$index] : null,
//                 'vat' => isset($vats[$index]) ? $vats[$index] : null,
//                 'discount' => isset($discounts[$index]) ? $discounts[$index] : 0,
//                 'image' => $variationImagePath,
//                 // الحقول الأخرى إذا كانت موجودة...
//             ];

//             ProductVariation::create($variationData);
//         }
//     }

//     // تسجيل استخدام الميزة
//     $currentCompany->subscription('main')->recordFeatureUsage('products');

//     session()->flash('alert-success', __('messages.product_added'));
//     return redirect()->route('products', ['company_uid' => $currentCompany->uid]);
// }

  


  public function store(Request $request)
    {
        //   dd($request->all());
        
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // التحقق من البيانات
        $validatedData = $request->validate([
            'name' => 'required|string|max:190',
            'unit_id' => 'required|integer',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'cover' => 'nullable|image',
            'images.*' => 'nullable|image',
            'quantity_alarm' => 'nullable|integer',
            'opening_stock' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer',
            'warehouse_id' => 'nullable|integer',
            'code' => 'nullable|string',
            'barcode' => 'nullable|string',
            'hide' => 'nullable|boolean',
            'variation_group_id' => 'nullable|integer',
            'colors' => 'nullable|array',
            // التحقق من التغييرات
            'price' => 'nullable|array',
            'price.*' => 'nullable|numeric',
            'variation_id' => 'nullable|array',
            'variation_id.*' => 'nullable|array',
            'variation_id.*.*' => 'nullable|integer|exists:variations,id',
            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable|integer',
            'sku' => 'nullable|array',
            'sku.*' => 'nullable|string|max:127',
            'vat' => 'nullable|array',
            'vat.*' => 'nullable|array',
            'vat.*.*' => 'nullable|integer|exists:tax_types,id',
            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric',
            'image' => 'nullable|array',
            'image.*' => 'nullable|image',
        ]);

        // معالجة صورة الغلاف الرئيسية (إن وجدت)
        $coverImagePath = null;
        if ($request->hasFile('cover')) {
            $coverImage = $request->file('cover');
            $coverImageName = time() . '_' . $coverImage->getClientOriginalName();
            $coverImage->storeAs('uploads/product/', $coverImageName, 'public');
            $coverImagePath = $coverImageName;
        }

        // معالجة الصور الإضافية (إن وجدت)
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('uploads/product/', $imageName, 'public');
                $imagesPaths[] = $imageName;
            }
        }

        // إنشاء المنتج الأساسي وتخزينه في قاعدة البيانات
        $product = Product::create([
            'name' => $request->name,
            'company_id' => $currentCompany->id,
            'unit_id' => $request->unit_id,
            'price'  => $request->price, // سعر المنتج الأساسي
            'description' => $request->description,
            'currency_id' => $request->currency_id,
            'cover' => $coverImagePath,
            'images' => !empty($imagesPaths) ? json_encode($imagesPaths) : null,
            'quantity_alarm' => $request->quantity_alarm,
            'opening_stock' => $request->opening_stock,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'warehouse_id' => $request->warehouse_id,
            'code' => $request->code,
            'barcode' => $request->barcode,
            'hide' => $request->hide,
            'variation_group_id' => $request->variation_group_id,
            'colors' => $request->colors ? json_encode($request->colors) : null,
        ]);

        // إضافة ضرائب المنتج الأساسي
        if ($request->has('taxes')) {
            $product->taxes()->sync($request->taxes);
        }

        // معالجة تغييرات المنتج
        if ($request->has('price')) {
            $prices = $request->input('price', []);
            $variationIds = $request->input('variation_id', []);
            $quantities = $request->input('quantity', []);
            $skus = $request->input('sku', []);
            $vats = $request->input('vat', []);
            $discounts = $request->input('discount', []);
            $variationImages = $request->file('image', []);

            foreach ($prices as $index => $variationPrice) {
                // معالجة صورة التغيير (إن وجدت)
                $variationImagePath = null;
                if (isset($variationImages[$index]) && $variationImages[$index]->isValid()) {
                    $variationImage = $variationImages[$index];
                    $variationImageName = time() . '_' . $variationImage->getClientOriginalName();
                    $variationImage->storeAs('uploads/product/', $variationImageName, 'public');
                    $variationImagePath = $variationImageName;
                }

                $variationData = [
                    'product_id' => $product->id,
                    'price' => $variationPrice,
                    'variation_id' => isset($variationIds[$index]) ? json_encode($variationIds[$index]) : null,
                    'quantity' => isset($quantities[$index]) ? $quantities[$index] : 0,
                    'sku' => isset($skus[$index]) ? $skus[$index] : null,
                    'vat' => isset($vats[$index]) ? json_encode($vats[$index]) : null,
                    'discount' => isset($discounts[$index]) ? $discounts[$index] : 0,
                    'image' => $variationImagePath,
                    // الحقول الأخرى إذا كانت موجودة...
                ];

                ProductVariation::create($variationData);
            }
        }

        // إعادة التوجيه مع رسالة نجاح
        session()->flash('alert-success', __('messages.product_added'));
        return redirect()->route('products.index');
    }




 
    public function edit(Request $request)
    {
        $product = Product::findOrFail($request->product);

        return view('application.products.edit', [
            'product' => $product,
        ]); 
    }

    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $product = Product::findOrFail($request->product);

        // Update the Expense
        $product->update([
            'name' => $request->name,
            'unit_id' => $request->unit_id,
            'price'  => $request->price,
            'description' => $request->description,
        ]);

        // Update custom field values
        $product->updateCustomFields($request->custom_fields);

        // Remove old Product Taxes
        $product->taxes()->delete();

        // Update Product Taxes
        if ($request->has('taxes')) {
            foreach ($request->taxes as $tax) {
                $product->taxes()->create([
                    'tax_type_id' => $tax
                ]);
            }
        }

        session()->flash('alert-success', __('messages.product_updated'));
        return redirect()->route('products', ['company_uid' => $currentCompany->uid]);
    }


    public function delete(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        
        $product = Product::findOrFail($request->product);

        // If the product already in use in Invoice Items
        // then return back and flash an alert message
        if ($product->invoice_items()->exists() && $product->invoice_items()->count() > 0) {
            session()->flash('alert-success', __('messages.product_cant_deleted_invoice'));
            return redirect()->route('products.edit', ['product' => $request->product, 'company_uid' => $currentCompany->uid]);
        }

        // If the product already in use in Estimate Items
        // then return back and flash an alert message
        if ($product->estimate_items()->exists() && $product->estimate_items()->count() > 0) {
            session()->flash('alert-success', __('messages.product_cant_deleted_estimate'));
            return redirect()->route('products.edit', ['product' => $request->product, 'company_uid' => $currentCompany->uid]);
        }

        // Delete Product Taxes from Database
        if ($product->taxes()->exists() && $product->taxes()->count() > 0) {
            $product->taxes()->delete();
        }

        // Delete Product from Database
        $product->delete();

        // Reduce feature
        $currentCompany->subscription('main')->reduceFeatureUsage('products');
        
        session()->flash('alert-success', __('messages.product_deleted'));
        return redirect()->route('products', ['company_uid' => $currentCompany->uid]);
    }
}