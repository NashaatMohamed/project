<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandCategories extends Model
{
    protected $table = 'brand_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_id',
        'category_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(ProductBrands::class,'brand_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ProductCategories::class,"category_id");
    }


}
