<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'bank_id', 
        'iban',
        'full_name',
        'additional_info',
        'status',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Define Relation with Bank Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

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
     * Scope a query to only include active Withdraw Accounts.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    /**
     * Scope a query to only include Withdraw Accounts of a given company.
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
}
