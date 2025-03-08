<?php

namespace App\Models;

use App\Casts\ImagesCast;
use App\Traits\HasCustomFields;
use App\Traits\HasTax;
use App\Traits\UUIDTrait;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasTax;
    use UUIDTrait;
    use HasCustomFields;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'unit_id',
        'price',
        'company_id',
        'description',
        'hide',
        //---------------------------------//
        'cover',
        'images',

        'quantity_alarm',
        'opening_stock',
//        'opening_stock_date',

        'warehouse_id',
        'category_id',
        'brand_id',

        'code',
        'barcode',

//        'sale_price',
//        'purchase_price',
//        'mrp',

        'variation_group_id',
        'colors',

    ];

    /**
     * Automatically cast attributes to given types
     *
     * @var array
     */
    protected $casts = [
        'price' => 'number',
        'colors'=>'json',
//        "images" => ImagesCast::Class
    ];

    protected $appends = ["image"];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['fields'];

    /**
     * Define Relation with Company Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    /**
     * Define Relation with category Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ProductCategories::class,'category_id');
    }


    /**
     * Define Relation with warehouse Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class,'warehouse_id');
    }

    /**
     * Define Relation with brand Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(ProductBrands::class,'brand_id');
    }

    /**
     * Define Relation with ProductUnit Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
        return $this->belongsTo(ProductUnit::class);
    }

    /**
     * Define Relation with InvoiceItem Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class,"product_id");
    }

    /**
     * Define Relation with EstimateItem Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estimate_items()
    {
        return $this->hasMany(EstimateItem::class);
    }

    /**
     * Define Relation with Product Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ProductVariations() : HasMany
    {
        return $this->hasMany(ProductVariation::class,'product_id');
    }

    public function hasColors()
    {
        return $this->productVariations()->whereHas('productVariationColors')->exists();
    }

    public function latestProductVariations() : HasMany
    {
        return $this->hasMany(ProductVariation::class,'product_id')->latest()->whereNotNull("parent_id");
    }

    /**
     * Set currency_code attribute from company
     *
     * @return string
     */
    public function getCurrencyCodeAttribute($value)
    {
        return $this->company->currency->short_code;
    }

    /**
     * Set formatted_created_at attribute by custom date format
     * from Company Settings
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('date_format', $this->company_id);
        return Carbon::parse($this->created_at)->format($dateFormat);
    }

    /**
     * Scope a query to only include Products of a given company.
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



    /**
     * @return string
     */
    protected function getImageAttribute(): string
    {
        return !empty($this->attributes["cover"]) ? env("APP_URL").'/uploads/product/' .$this->attributes["cover"]   : "https://daleel-madani.org/sites/default/files/default_images/daleel-madani-default-cover-image.png";
    }


    public function incomingStock() :   HasMany
    {
        return $this->hasMany(StockMovement::class,"product_id")->where("type","in");
    }

    public function outgoingStock() :   HasMany
    {
        return $this->hasMany(StockMovement::class,"product_id")->where("type","out");
    }

    public function stockMovements() : HasMany
    {
        return $this->hasMany(StockMovement::class,"product_id");
    }



    public function getCustomers()
    {
        $invoice_ids = $this->invoice_items->pluck("invoice_id");
        $invoices = Invoice::whereIn("id", $invoice_ids)->get();
        $data = [];

        foreach ($invoices as $invoice) {
            $customer_id = $invoice->customer->id;

            if (!isset($data[$customer_id])) {
                $data[$customer_id] = [
                    "customer" => $invoice->customer,
                    "total_paid" => $invoice->items->where("product_id", $this->id)->sum("total"),
                    "quantity_purchased" => $invoice->items->where("product_id", $this->id)->sum("quantity"),
                ];
            } else {
                // تحديث البيانات للعميل الموجود
                $data[$customer_id]["total_paid"] += $invoice->items->where("product_id", $this->id)->sum("total");
                $data[$customer_id]["quantity_purchased"] += $invoice->items->where("product_id", $this->id)->sum("quantity");
            }
        }

        return array_values($data); // إعادة القيم بدون المفاتيح لتكون المصفوفة نظيفة
    }




}
