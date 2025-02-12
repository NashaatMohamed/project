<?php
// app/Models/VariationAttributes.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationAttributes extends Model
{
    protected $table = 'variation_attributes';

    protected $fillable = [
        'name',
        'variation_id',
        'company_id',
        'sort'
    ];

    /**
     * علاقة متعدد إلى واحد مع نموذج Variations
     */
    public function variation()
    {
        return $this->belongsTo(Variations::class, 'variation_id');
    }

    /**
     * نطاق لاستعلام الخصائص حسب التغيير
     */
    public function scopeFindByVariation($query, $variation_id)
    {
        return $query->where('variation_id', $variation_id);
    }

    /**
     * قائمة للتحديد في مكتبة Select2
     */
    public static function getSelect2Array()
    {
        return self::select('id', 'name AS text')->get();
    }
}