<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationGroup extends Model
{
    protected $table = 'variation_groups';

    protected $fillable = [
        'name',
        'company_id',
        'sort',
    ];

    /**
     * العلاقة مع التغييرات (Variations).
     */
    public function groupVariations()
    {
        return $this->hasMany(GroupVariation::class, 'variation_group_id');
    }

   
    public function scopeFindByCompany($query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

 
    public static function getSelect2Array($company_id)
    {
        return self::findByCompany($company_id)
            ->pluck('name', 'id')
            ->toArray();
    }
}