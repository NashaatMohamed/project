<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Product\Store;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductBrands;
use App\Models\ProductUnit;
use App\Models\ProductVariation;
use App\Models\ProductVariationColor;
use App\Models\Warehouses;
use App\Services\Products\ProductService;
use App\Services\StockMovement\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller
{

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;

    }
    public function index(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        $products = $this->productService->getProductIndex($currentCompany);

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
//        dd($request->validated());
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        $data = $request->validated() + ['company_id' => $currentCompany->id];

        $data['colors_quantity'] = array_key_exists("colors_quantity",$data) ? array_map(fn($q) => array_values(array_filter($q)), $data['colors_quantity']) : [];
        $product = $this->storeProducts($data);

        if (!empty($data['variation_id'])) {
            $product->opening_stock = 0;
            foreach ($data['variation_id'] as $index => $variationGroup) {
                $productVariation = ProductVariation::query()->create([
                    "product_id" => $product->id,
                    "price" => $data['variation_price'][$index] ?? 0,
                    "quantity" => $data['quantity'][$index] ?? 0,
                    "base_quantity" => $data['quantity'][$index] ?? 0,
                    "sku" => $data['sku'][$index] ?? null,
                    "company_id" => $currentCompany->id,
                    "variations_json" => json_encode($variationGroup)
                ]);

                $product->opening_stock += $productVariation->quantity;
                $product->price += $productVariation->price;
                $product->save();

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
            "opening_stock" => $data["opening_stock"] ?? 0,
            "quantity_alarm" => $data["quantity_alarm"] ?? null,
            "brand_id" => $data["brand_id"] ?? null,
            "description" => $data["description"] ?? null,
            "code" => $data['code'] ?? null,
            "barcode" => $data['barcode'] ?? null,
            "company_id" => $data['company_id'] ?? null,
            "category_id" => $data['category_id'] ?? null,
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

        $product = $this->productService->getShowVariation($currentCompany, $id);

        return view('application.products.show_first_variation', [
            'product_variation' => $product,
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
        $data = $request->validated() + ['company_id' => $currentCompany->id];

        $data['colors_quantity'] = $this->productService->normalizeColorsQuantity($data);

        $product = $this->productService->fetchProduct($id, $currentCompany->id);

        DB::beginTransaction();

        try {
            $this->productService->updateProduct($product, $data);

            if (empty($data['variation_id'])) {
                $product->productVariations()->delete();
            } else {
                $this->productService->updateProductVariations($product, $data, $user, $currentCompany);
            }

            DB::commit();

            return redirect()->route('products', ['company_uid' => $currentCompany->uid])
                ->with('success', __('global.record_updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('global.update_failed') . $e->getMessage());
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
