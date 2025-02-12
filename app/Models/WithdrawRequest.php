<?php

namespace App\Models;

use App\Traits\UUIDTrait;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    use UUIDTrait;

    // Statuses
    const STATUS_REQUESTED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_DECLINED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'withdraw_account_id',
        'wallet_id',
        'requested_by', 
        'approved_by',
        'wallet_currency',
        'amount_to_deposit',
        'amount_to_decrease',
        'status',
        'notes',
        'fee',
        'approved_at',
        'declined_at',
        'declined_reason',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'declined_at' => 'datetime',
    ];

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
     * Define Relation with Withdraw Account Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function withdraw_account()
    {
        return $this->belongsTo(WithdrawAccount::class)->withDefault();
    }

    /**
     * Define Relation with Wallet Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Define Relation with User Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requested_by_user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Define Relation with User Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approved_by_user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get Status Attribute
     * 
     * @return string
     */
    public function getHtmlStatusAttribute()
    {
        if ($this->status == self::STATUS_REQUESTED) {
            return '<div class="badge badge-info">'.__('messages.requested').'</div>';
        } else if ($this->status == self::STATUS_APPROVED) {
            return '<div class="badge badge-success">'.__('messages.approved').'</div>';
        } else if ($this->status == self::STATUS_DECLINED) {
            return '<div class="badge badge-danger">'.__('messages.declined').'</div>';
        }
    }

    /**
     * Scope a query to only include active Withdraw Requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        $query->where('status', self::STATUS_REQUESTED);
    }

    /**
     * Scope a query to only include approved Withdraw Requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include declined Withdraw Requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeclined($query)
    {
        $query->where('status', self::STATUS_DECLINED);
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
