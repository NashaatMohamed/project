<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariations extends Model
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
        'variation_id',    // الحقل الجديد
        'vat',             // الحقل الجديد
        'variations_json', // الحقل الاختياري
    ];

    protected $casts = [
        'variations_json' => 'json',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'quantity' => 'decimal:2',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}