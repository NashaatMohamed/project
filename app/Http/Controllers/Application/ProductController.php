<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Product\Store;
use App\Http\Requests\Application\Product\Update;
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
        $product = Product::query()->create($data);
        return $product;
        return redirect()->route('products.index')->with('success', __('global.record_added'));
    }


}