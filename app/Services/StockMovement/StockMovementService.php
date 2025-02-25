<?php

namespace App\Services\StockMovement;

use App\Models\ProductVariation;

class StockMovementService
{
    /**
     * Handles stock movement when manually editing a product variation's quantity.
     */
    public static function handleStockMovementForManualEdit(ProductVariation $productVariation, int $quantity, int $userId, int $companyId): void
    {
        $quantityDifference = abs($quantity - $productVariation->quantity);

        if ($quantityDifference > 0) {
            $type = $quantity > $productVariation->quantity ? "in" : "out";
            $referenceType = "4"; // Manual edit

            $reference = "The user with ID {$userId} has edited the quantity of the product variation (" .
                "{$productVariation->getFullProductName()}) from {$productVariation->quantity} to {$quantity}";

            self::recordStockMovement($productVariation, $quantityDifference, $type, $reference, $referenceType, $userId, $companyId);
        }
    }

    /**
     * Handles stock movement when selling a product variation via an invoice.
     */
    public static function handleStockMovementForInvoice(int $productVariationId, int $quantity, int $userId, int $companyId, $invoice): void
    {
        $productVariation = ProductVariation::find($productVariationId);

        if (!$productVariation) {
            return; // Prevents errors if the product variation is not found
        }

        $type = "out";
        $referenceType = "1"; // Sale

        $reference = "The user with ID {$userId} has sold {$quantity} of the product variation (" .
            "{$productVariation->getFullProductName()}) to the invoice number {$invoice->invoice_number}";

        self::recordStockMovement($productVariation, $quantity, $type, $reference, $referenceType, $userId, $companyId, $invoice->id);
    }

    /**
     * Records a stock movement entry.
     */
    private static function recordStockMovement(ProductVariation $productVariation, int $quantity, string $type, string $reference, string $referenceType,
                                                int $userId, int $companyId, int $invoiceId = null): void
    {
        $productVariation->stockMovements()->create([
            "quantity" => $quantity,
            "type" => $type,
            "reference" => $reference,
            "reference_type" => $referenceType,
            "user_id" => $userId,
            "company_id" => $companyId,
            "product_id" => $productVariation->product_id,
            "invoice_id" => $invoiceId,
        ]);
    }
}
