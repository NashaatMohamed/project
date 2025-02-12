<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreditNoteRefund extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'credit_note_id',
        'payment_method_id',
        'refund_date',
        'amount',
        'notes',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'refund_date' => 'date',
        'amount' => 'integer',
    ];

    /**
     * Define Relation with Credit Note Model
     *  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function credit_note()
    {
        return $this->belongsTo(CreditNote::class);
    }

    /**
     * Define Relation with Payment Method Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Set formatted_refund_date attribute by custom date format
     * from Company Settings
     *
     * @return string
     */
    public function getFormattedRefundDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('date_format', $this->credit_note->company_id);
        return Carbon::parse($this->refund_date)->format($dateFormat);
    }

    /**
     * Set currency_code attribute from customer
     *
     * @return string
     */
    public function getCurrencyCodeAttribute($value)
    {
        return $this->credit_note->customer->currency->short_code;
    }
}
