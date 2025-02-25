<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{

    protected $table = "stock_movements";

    protected $fillable = ["product_variation_id", "user_id", "invoice_id", "quantity", "type", "reference", "reference_type","product_id","company_id"];


    public function productVariation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, "product_variation_id", "id");
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, "invoice_id", "id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

}
