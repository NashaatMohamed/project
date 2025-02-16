<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

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
        if (!$this->parent_id) {
            return $this->product->name . ' ' . ($this->variationAttribute->name ?? '');
        }

        return $this->product->name . ' ' . $this->getAllAncestorsName()->implode(' ') . ' ' . ($this->variationAttribute->name ?? '');
    }

    /**
     * Get the product associated with the variation.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variation attribute.
     */
    public function variationAttribute(): BelongsTo
    {
        return $this->belongsTo(VariationAttributes::class, 'variation_attribute_id');
    }

    /**
     * Get child variations.
     */
    public function childVariation(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'parent_id', 'id');
    }

    /**
     * Get parent variation.
     */
    public function parentVariation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'parent_id', 'id');
    }

    /**
     * Recursively get all descendants.
     */
    private function traverseDescendants()
    {
        $descendants = collect();
        $stack = [$this];

        while ($stack) {
            $current = array_pop($stack);
            foreach ($current->childVariation as $child) {
                $descendants->push($child);
                $stack[] = $child;
            }
        }

        return $descendants;
    }

    /**
     * Recursively get all ancestors.
     */

    private  function traverseAncestors($ancestors = null)
    {
        $ancestors = $ancestors ?? collect();

        if ($this->parentVariation) {
            $ancestors->push($this->parentVariation);
            return $this->parentVariation->traverseAncestors($ancestors);
        }

        return $ancestors;
    }

    /**
     * Get all ancestors.
     */
    public function getAllAncestors()
    {
        return $this->traverseAncestors()->reverse();
    }

    /**
     * Get all descendants.
     */
    public function getAllDescendants()
    {
        return $this->traverseDescendants();
    }

    /**
     * Get all ancestors' names.
     */
    public function getAllAncestorsName()
    {
        return $this->getAllAncestors()->map(function ($ancestor) {
            return $ancestor->variationAttribute->name ?? '';
        });
    }
}
