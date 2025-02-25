<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends Model
{
    protected $table = 'product_variations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'price',
        'tax_id',
        'discount',
        'image',
        'quantity',
        'sku',
        'variation_id',
        'vat',
        'variations_json',
        'variation_attribute_id',
        'colors',
        'parent_id',
        'company_id',
    ];

    protected $casts = [
        'variations_json' => 'json',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'quantity' => 'decimal:2',
        'colors' => 'json',
    ];

    /**
     * Get the full name of the product variation.
     */

    public function getFullProductName(): string
    {
        $variation_attributes_names = $this->getVariationAttributes()->map(function ($value) {
            return $value->name;
        });
        return $this->product->name . ' ' . $variation_attributes_names->implode(' ');
    }

    /**
     * Get the product associated with the variation.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariationColors() : HasMany
    {
        return $this->hasMany(ProductVariationColor::class, 'product_variation_id');
    }

    /**
     * Get the variation attribute.
     */
    public function variationAttribute(): BelongsTo
    {
        return $this->belongsTo(VariationAttributes::class, 'variation_attribute_id');
    }

    public function getVariationAttributes(): \Illuminate\Support\Collection
    {
        return collect(json_decode($this->variations_json, true))->map(function ($value) {
            return VariationAttributes::where('id', $value)->first();
        });
    }

    public function stockMovements() : HasMany
    {
        return $this->hasMany(StockMovement::class, 'product_variation_id');
    }

}
