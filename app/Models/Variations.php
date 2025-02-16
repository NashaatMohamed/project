<?php

 // app/Models/Variations.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variations extends Model
{
    protected $table = 'variations';

    protected $fillable = [
        'name',
        'company_id',
        'sort'
    ];

    protected $appends = ["children"];

    /**
     * علاقة واحد إلى متعدد مع نموذج المنتج (Product)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * علاقة واحد إلى متعدد مع نموذج VariationAttributes
     */
    public function variationAttributes()
    {
        return $this->hasMany(VariationAttributes::class, "variation_id");
    }

    /**
     * علاقة متعدد إلى متعدد مع نموذج Variations عبر جدول group_variations
     */
    public function variationGroup() : BelongsToMany
    {
        return $this->belongsToMany(Variations::class, 'group_variations')
                    ->withTimestamps();
    }


    public function groupVariations()  :HasMany
    {
        return $this->hasMany(GroupVariation::class, 'variation_group_id');
    }
    /**
     * وصول accessor لـ "children"
     */
    public function getChildrenAttribute()
    {
        return $this->variationAttributes()->orderBy("sort", "asc")->get(['id', 'name AS text']);
    }

    /**
     * نطاق لاستعلام التغييرات حسب الشركة
     */
    public function scopeFindByCompany($query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    /**
     * قائمة للتحديد في مكتبة Select2
     */
    public static function getSelect2Array($company_id)
    {
        return self::findByCompany($company_id)
                   ->pluck('name AS text', 'id');
    }
}