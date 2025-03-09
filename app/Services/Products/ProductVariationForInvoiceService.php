<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\StockMovement;
use App\Services\StockMovement\StockMovementService;
use Illuminate\Support\Facades\DB;

class ProductVariationForInvoiceService
{


    public function handleInvoiceProduct($products, $quantities, $prices, $discounts, $totals, $taxes, $invoice, $currentCompany, $user, $update = false)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            for ($i = 0; $i < count($products); $i++) {
                $productVariation = $this->getOrCreateProductVariation($products[$i], $prices[$i], $currentCompany);

                if ($update) {
                    $this->revertStockMovementForUpdate($productVariation, $invoice, $currentCompany);
                }

                $this->updateProductStock($productVariation, $quantities[$i]);

                $invoiceItem = $this->createInvoiceItem($invoice, $productVariation, $quantities[$i], $discounts[$i] ?? 0, $prices[$i], $totals[$i], $currentCompany);

                $this->addTaxesToInvoiceItem($invoiceItem, $taxes[$i] ?? []);

                StockMovementService::handleStockMovementForInvoice(
                    $productVariation->id,
                    $quantities[$i] ?? 0,
                    $user->id,
                    $currentCompany->id,
                    $invoice
                );
            }

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            throw $e; // Re-throw the exception to handle it at a higher level
        }
    }

    /**
     * Get or create a product variation
     */
    private function getOrCreateProductVariation($productId, $price, $currentCompany)
    {
        return ProductVariation::firstOrCreate(
            ['id' => $productId, 'company_id' => $currentCompany->id],
            ["name" => $productId, 'price' => $price, 'hide' => 1]
        );
    }

    /**
     * Revert stock movement for update
     */
    private function revertStockMovementForUpdate($productVariation, $invoice, $currentCompany)
    {
        $stockMovement = StockMovement::query()
            ->where("product_variation_id", $productVariation->id)
            ->where("reference_type", 1)
            ->where("company_id", $currentCompany->id)
            ->where("invoice_id", $invoice->id)
            ->first();

        if ($stockMovement) {
            $productVariation->quantity += $stockMovement->quantity;
            $productVariation->product->opening_stock += $stockMovement->quantity;
            $productVariation->product->save();
            $productVariation->save();
            $stockMovement->delete();
        }
    }

    /**
     * Update product stock
     */
    private function updateProductStock($productVariation, $quantity)
    {
        $productVariation->quantity -= $quantity;
        $productVariation->product->opening_stock -= $quantity;
        $productVariation->product->save();
        $productVariation->save();
    }

    /**
     * Create an invoice item
     */
    private function createInvoiceItem($invoice, $productVariation, $quantity, $discount, $price, $total, $currentCompany)
    {
        return $invoice->items()->create([
            'product_id' => $productVariation->product_id,
            "product_variation_id" => $productVariation->id,
            'company_id' => $currentCompany->id,
            'quantity' => $quantity,
            'discount_type' => 'percent',
            'discount_val' => $discount,
            'price' => $price,
            'total' => $total,
        ]);
    }

    /**
     * Add taxes to an invoice item
     */
    private function addTaxesToInvoiceItem($invoiceItem, $taxes)
    {
        if (!empty($taxes)) {
            foreach ($taxes as $tax) {
                $invoiceItem->taxes()->create([
                    'tax_type_id' => $tax
                ]);
            }
        }
    }
//    private function getProduct($currentCompany, $i, $products, $prices)
//    {
//
//        // old product not product_variation
////        $product = Product::firstOrCreate(
////            ['id' => $products[$i], 'company_id' => $currentCompany->id],
////            ['name' => $products[$i], 'price' => $prices[$i], 'hide' => 1]
////        );
//
//        $product_variation = ProductVariation::firstOrCreate(
//            ['id' => $products, 'company_id' => $currentCompany->id],
//            ['name' => $product, 'price' => 0, 'hide' => 1]
//        );
//
//        return $product_variation;

//    }
}
