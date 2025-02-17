<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductBrands;
use App\Models\ProductUnit;
use App\Models\ProductVariation;
use App\Models\VariationAttributes;
use App\Models\Warehouses;
use App\ProductVariationColor;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Product\Store;
use App\Http\Requests\Application\Product\Update;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{

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

    public function store(Store $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        $data = $request->validated() + ['company_id' => $currentCompany->id];

        $data['colors_quantity'] = array_map(fn($q) => array_values(array_filter($q)), $data['colors_quantity']);
        // إنشاء المنتج الرئيسي
        $product = $this->storeProducts($data);

        // التحقق من وجود بيانات تنوعات المنتج
        if (!empty($data['variation_id'])) {
            foreach ($data['variation_id'] as $index => $variationGroup) {
                $productVariation = ProductVariation::query()->create([
                    "product_id" => $product->id,
                    "price" => $data['variation_price'][$index] ?? 0,
                    "quantity" => $data['quantity'][$index] ?? 0,
                    "sku" => $data['sku'][$index] ?? null,
                    "company_id" => $currentCompany->id,
                    "variations_json" => json_encode($variationGroup)
                ]);

                // تخزين الألوان إذا كان هناك ProductVariation صالح
                if ($productVariation && !empty($data['colors'])) {
                    $this->storeColors($productVariation->id, $data, $index);
                }
            }
        }

        return redirect()->route('products', ['company_uid' => $currentCompany->uid])
            ->with('success', __('global.record_added'));
    }

    /**
     * تخزين الألوان المرتبطة بـ ProductVariation
     */
    private function storeColors($productVariationId, $data, $index)
    {
        if (empty($data['colors']) || empty($data['colors_quantity'])) {
            return;
        }

        foreach ($data['colors'] as $colorName) {
            if (!empty($data['colors_quantity'][$colorName][$index])) {
                ProductVariationColor::query()->create([
                    "product_variation_id" => $productVariationId,
                    "color" => $colorName,
                    "quantity" => $data['colors_quantity'][$colorName][$index]
                ]);
            }
        }
    }

    /**
     * إنشاء المنتج الرئيسي
     */
    private function storeProducts($data)
    {
        return Product::query()->create([
            "name" => $data["name"] ?? null,
            "price" => $data['price'] ?? null,
            "unit_id" => $data["unit_id"] ?? null,
            "warehouse_id" => $data["warehouse_id"] ?? null,
            "opening_stock" => $data["opening_stock"] ?? null,
            "quantity_alarm" => $data["quantity_alarm"] ?? null,
            "brand_id" => $data["brand_id"] ?? null,
            "description" => $data["description"] ?? null,
            "code" => $data['code'] ?? null,
            "barcode" => $data['barcode'] ?? null,
            "company_id" => $data['company_id'] ?? null,
//            "variation_group_id" => (int) $data['variation_group_id'] == 0 ? null : (int) $data['variation_group_id'],
        ]);
    }


    public function show($company_uid, $id)
    {
        $currentCompany = Company::where('uid', $company_uid)->firstOrFail();
        $product = Product::with(['ProductVariations', 'unit'])
            ->where('id', $id)
            ->where('company_id', $currentCompany->id)
            ->firstOrFail();

        return view('application.products.details', [
            'product' => $product,
            'currentCompany' => $currentCompany, // Assuming $currentCompany is available
        ]);
    }


    public function showFirstVariation($company_uid, $id)
    {
        $currentCompany = Company::where('uid', $company_uid)->firstOrFail();

        $product = Product::with(['ProductVariations' => function ($query) {
            $query->orderBy('id', 'asc')->limit(1);
        }])
            ->where('id', $id)
            ->where('company_id', $currentCompany->id)
            ->firstOrFail();


        return view('application.products.show_first_variation', [
            'product' => $product,
            'currentCompany' => $currentCompany,
        ]);
    }


    public function edit($company_uid, $id)
    {
        $currentCompany = Company::where('uid', $company_uid)->firstOrFail();

        $product = Product::with(['ProductVariations.productVariationColors'])
            ->where('id', $id)
            ->where('company_id', $currentCompany->id)
            ->firstOrFail();

        // Fetch necessary data for dropdowns (e.g., units, brands, warehouses)
        $units = ProductUnit::where('company_id', $currentCompany->id)->get();
        $brands = ProductBrands::where('company_id', $currentCompany->id)->get();
        $warehouses = Warehouses::where('company_id', $currentCompany->id)->get();

        // Pass data to the edit view
        return view('application.products.edit', [
            'product' => $product,
            'units' => $units,
            'brands' => $brands,
            'warehouses' => $warehouses,
            'currentCompany' => $currentCompany,
        ]);
    }

    public function update(Store $request, $company_uid, $id)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        $data = $request->validated();

        // Fetch the product
        $product = Product::where('id', $id)
            ->where('company_id', $currentCompany->id)
            ->firstOrFail();

        // Update the product
        $product->update([
            "name" => $data["name"] ?? null,
            "price" => $data['price'] ?? null,
            "unit_id" => $data["unit_id"] ?? null,
            "warehouse_id" => $data["warehouse_id"] ?? null,
            "opening_stock" => $data["opening_stock"] ?? null,
            "quantity_alarm" => $data["quantity_alarm"] ?? null,
            "brand_id" => $data["brand_id"] ?? null,
            "description" => $data["description"] ?? null,
            "code" => $data['code'] ?? null,
            "barcode" => $data['barcode'] ?? null,
//            "variation_group_id" => (int) $data['variation_group_id'] == 0 ? null : (int) $data['variation_group_id'],
        ]);

        if (empty($data['variation_id'])) {
            $product->productVariations()->delete();
        } else {
            foreach ($data['variation_id'] as $index => $variationGroup) {
                $variationsJson = json_encode($variationGroup);

                $productVariation = ProductVariation::updateOrCreate(
                    [
                        "product_id" => $product->id,
                        "variations_json" => $variationsJson,
                    ],
                    [
                        "price" => $data['variation_price'][$index] ?? 0,
                        "quantity" => $data['quantity'][$index] ?? 0,
                        "sku" => $data['sku'][$index] ?? null,
                        "company_id" => $currentCompany->id,
                    ]
                );

                // Update or create colors for the ProductVariation
                if ($productVariation && !empty($data['colors'])) {
                    $this->updateColors($productVariation->id, $data, $index);
                }
            }
        }

        return redirect()->route('products', ['company_uid' => $currentCompany->uid])
            ->with('success', __('global.record_updated'));
    }

    /**
     * Update colors for a ProductVariation
     */
    private function updateColors($productVariationId, $data, $index)
    {
        if (empty($data['colors']) || empty($data['colors_quantity'])) {
            return;
        }

        foreach ($data['colors'] as $colorName) {
            ProductVariationColor::updateOrCreate(
                [
                    "product_variation_id" => $productVariationId,
                    "color" => $colorName,
                ],
                [
                    "quantity" => $data['colors_quantity'][$colorName][$index] ?? 0,
                ]
            );
        }
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