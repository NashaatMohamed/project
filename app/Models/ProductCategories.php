<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductCategories extends Model
{
    protected $table = 'product_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'company_id',
        'parent_id'
    ];

    /**
     * Define Relation with Product Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Define Relation with Company Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subCategories()
    {
        return $this->hasMany(ProductCategories::class, "parent_id", "id");
    }


    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategories::class, "id", "parent_id");
    }


    /**
     * List Product Units for Select2 Javascript Library
     *
     * @return json
     */
    public static function getSelect2Array($company_id,$brand_id)
    {
        return self::findByCompany($company_id)->where(function($w) use($brand_id){
            if($brand_id)
                $w->whereIn("id", BrandCategories::select("category_id")->where("brand_id",$brand_id)->get());
        })->select('id', 'name AS text')
            ->get();
    }

    /**
     * @return Collection
     */
    public function inc()
    {
        return $this->subCategories()->select(["id", "name as text", "parent_id"]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function CategoriesTree($data, $id = null)
    {
        return ProductCategories::where('company_id', $data["company_id"])
            ->whereNull('parent_id')
            ->with('inc.inc')
            ->get(["id", "name as text", "parent_id"])
            ->toArray();
    }



    /**
     * Scope a query to only include Product Units of a given company.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param int $company_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindByCompany($query, $company_id)
    {
        $query->where('company_id', $company_id);
    }

    public function scopeFindByBrand($query, $brand_id)
    {
        $query->whereIn('id', BrandCategories::select("category_id")->where("brand_id",$brand_id)->get());
    }




    /**
     * @param $query
     * @param $data
     * @return mixed
     */
    public function scopeFilter($query, $data): mixed
    {
        $query->where('company_id', $data["company_id"]);

        if (array_key_exists('id', $data) and $id = $data['id']) {
            $query->where("id", $id);
        }

        if (array_key_exists('category-id', $data) and $parent_id = $data['category-id']) {
            $query->where("parent_id", $parent_id);
        }

        if (array_key_exists('name', $data) and $name = $data['name']) {
            $query->where(function ($q) use ($name) {
                $q->where("name", "like", '%' . $name . '%');
            });

        }


        if (array_key_exists('main_categories', $data)) {
            $query->whereNull("parent_id");
        }

        if (array_key_exists('sub_categories', $data)) {
            $query->whereNotNull("parent_id");
        }


        return $query;
    }


}