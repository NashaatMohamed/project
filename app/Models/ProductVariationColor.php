<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariationColor extends Model
{

    protected $table = 'product_variation_colors';

    protected $fillable = [
        'product_variation_id',
        'color',
        "quantity"
    ];
}
