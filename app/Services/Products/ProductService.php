<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\ProductVariation;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductService
{


    public function getProductIndex($currentCompany): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // old logic for products
//        $products = QueryBuilder::for(Product::findByCompany($currentCompany->id))
//            ->where('hide', false)
//            ->allowedFilters([
//                AllowedFilter::partial('name'),
//                AllowedFilter::exact('unit_id'),
//            ])
//            ->oldest()
//            ->paginate()
//            ->appends(request()->query());

        // new logic for get product_variations not product

        $products = ProductVariation::query()->where("company_id", $currentCompany->id)
            ->latest()
            ->paginate();

        return $products;
    }


    public function getShowVariation($currentCompany, $id)
    {
        // old logic for products
//        $product = Product::with(['ProductVariations' => function ($query) {
//            $query->orderBy('id', 'asc')->limit(1);
//        }])
//            ->where('id', $id)
//            ->where('company_id', $currentCompany->id)
//            ->firstOrFail();

        // new logic for get product_variations not product
        $product = ProductVariation::where('id', $id)
            ->where('company_id', $currentCompany->id)
            ->firstOrFail();

        return $product;


    }
}