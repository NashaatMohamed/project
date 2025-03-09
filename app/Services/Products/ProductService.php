<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationColor;
use App\Services\StockMovement\StockMovementService;
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

    public function normalizeColorsQuantity($data): array
    {
        return array_key_exists("colors_quantity", $data)
            ? array_map(fn($q) => array_values(array_filter($q)), $data['colors_quantity'])
            : [];
    }

    public function fetchProduct($id, $companyId)
    {
        return Product::where('id', $id)
            ->where('company_id', $companyId)
            ->firstOrFail();
    }

    public function updateProduct($product, $data)
    {
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
        ]);
    }

    public function updateProductVariations($product, $data, $user, $currentCompany)
    {
        $updatedVariationIds = [];

        $product->opening_stock = 0;
        $product->price = 0;

        foreach ($data['variation_id'] as $index => $variationGroup) {
            $variationsJson = json_encode($variationGroup);

            $productVariation = $product->productVariations()->where('variations_json', $variationsJson)->first();

            if ($productVariation) {
                $this->updateExistingProductVariation($productVariation, $data, $index, $user, $currentCompany);
            } else {
                $productVariation = $this->createNewProductVariation($product, $data, $index, $currentCompany, $variationsJson);
            }

            $updatedVariationIds[] = $productVariation->id;

            $product->opening_stock += $productVariation->quantity;
            $product->price += (float)$productVariation->price;
            $product->save();

            $this->handleProductVariationColors($productVariation, $data, $index);
        }

        $product->productVariations()
            ->whereNotIn('id', $updatedVariationIds)
            ->delete();
    }

    public function updateExistingProductVariation($productVariation, $data, $index, $user, $currentCompany)
    {
        StockMovementService::handleStockMovementForManualEdit(
            $productVariation,
            $data['quantity'][$index] ?? 0,
            $user->id,
            $currentCompany->id
        );

        $productVariation->update([
            "price" => $data['variation_price'][$index] ?? 0,
            "quantity" => $data['quantity'][$index] ?? 0,
            "sku" => $data['sku'][$index] ?? null,
        ]);
    }

    public function createNewProductVariation($product, $data, $index, $currentCompany, $variationsJson)
    {
        return ProductVariation::create([
            "product_id" => $product->id,
            "price" => $data['variation_price'][$index] ?? 0,
            "quantity" => $data['quantity'][$index] ?? 0,
            "sku" => $data['sku'][$index] ?? null,
            "company_id" => $currentCompany->id,
            "variations_json" => $variationsJson
        ]);
    }


    public function handleProductVariationColors($productVariation, $data, $index)
    {
        if (empty($data['colors']) || empty($data['colors_quantity'])) {
            ProductVariationColor::where('product_variation_id', $productVariation->id)->delete();
        }

        if (!empty($data['colors'])) {
            $this->updateColors($productVariation->id, $data, $index);
        }
    }


    public function updateColors($productVariationId, $data, $index)
    {
        ProductVariationColor::where('product_variation_id', $productVariationId)->delete();

        foreach ($data['colors'] as $colorName) {
            ProductVariationColor::create([
                "product_variation_id" => $productVariationId,
                "color" => $colorName,
                "quantity" => $data['colors_quantity'][$colorName][$index] ?? 0,
            ]);
        }
    }

}
