<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupVariation extends Model
{

    protected $table = 'group_variations';

    protected $fillable = [
        'variation_id',
        'variation_group_id',
        'sort',
    ];

    /**
     * العلاقة مع التغييرات (Variations).
     */
    public function variations()
    {
        return $this->belongsTo(Variations::class, 'variation_id');
    }

    /**
     * العلاقة مع مجموعات التغييرات (VariationGroup).
     */
    public function variationGroup()
    {
        return $this->belongsTo(VariationGroup::class, 'variation_group_id');
    }
}
