<?php

namespace App\Models;

use App\Traits\CustomPDFFields;
use App\Traits\HasCustomFields;
use App\Traits\UUIDTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use UUIDTrait;
    use HasCustomFields;
    use CustomPDFFields;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'customer_id',
        'invoice_id',
        'credit_note_id',
        'payment_method_id',
        'transaction_reference',
        'payment_date',
        'payment_number',
        'amount',
        'notes',
        'private_notes',
        'is_archived',
    ]; 

    /**
     * Automatically cast date attributes to Carbon
     * 
     * @var array
     */
    protected $dates = [
        'created_at', 
        'updated_at', 
        'payment_date'
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'is_archived' => 'boolean',
    ];

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
     * Define Relation with Invoice Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

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
     * Define Relation with Customer Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Define Relation with PaymentMethod Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Customized strpos helper function for excluding prefix 
     * from payment number
     * 
     * @param string $haystack
     * @param string $needle
     * @param int $number
     * 
     * @return string
     */
    private function strposX($haystack, $needle, $number)
    {
        if ($number == '1') {
            return strpos($haystack, $needle);
        } elseif ($number > '1') {
            return strpos(
                $haystack,
                $needle,
                $this->strposX($haystack, $needle, $number - 1) + strlen($needle)
            );
        } else {
            return error_log('Error: Value for parameter $number is out of range');
        }
    }

    /**
     * Helper function for getting the next Payment Number
     * by searching the database and increase 1
     * 
     * @param string $prefix
     * 
     * @return string
     */
    public static function getNextPaymentNumber($company_id, $prefix)
    {
        // Get the last created order
        $payment = Payment::findByCompany($company_id)->where('payment_number', 'LIKE', $prefix . '-%')
                    ->orderBy('created_at', 'desc')
                    ->first();
        if (!$payment) {
            // We get here if there is no order at all
            // If there is no number set it to 0, which will be 1 at the end.
            $number = 0;
        } else {
            $number = explode("-", $payment->payment_number);
            $number = $number[1];
        }
        // If we have ORD000001 in the database then we only want the number
        // So the substr returns this 000001

        // Add the string in front and higher up the number.
        // the %05d part makes sure that there are always 6 numbers in the string.
        // so it adds the missing zero's when needed.

        return sprintf('%06d', intval($number) + 1);
    }

    /**
     * Set payment_num attribute
     *
     * @return int
     */
    public function getPaymentNumAttribute()
    {
        $position = $this->strposX($this->payment_number, "-", 1) + 1;
        return substr($this->payment_number, $position);
    }

    /**
     * Set payment_prefix attribute
     * 
     * @return string
     */
    public function getPaymentPrefixAttribute ()
    {
        return $this->id 
            ? explode("-", $this->payment_number)[0]
            : CompanySetting::getSetting('payment_prefix', $this->company_id);
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
     * Set formatted_payment_date attribute by custom date format
     * from Company Settings
     *
     * @return string
     */
    public function getFormattedPaymentDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('date_format', $this->company_id);
        return Carbon::parse($this->payment_date)->format($dateFormat);
    }

    /**
     * Get currency_code attribute
     * 
     * @return string
     */
    public function getCurrencyCodeAttribute($value)
    {
        return $this->customer
            ? $this->customer->currency->short_code
            : $this->company->currency->short_code;
    }

    /**
     * Scope a query to only include Payments of a given company.
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
     * Scope a query to only include Payments of a given customer.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param int $customer_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindByCustomer($query, $customer_id)
    {
        $query->where('customer_id', $customer_id);
    }

    /**
     * Scope a query to only return Payments which has payment_date
     * greater or equal then given date
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param Date $from
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFrom($query, $from)
    {
        $query->where('payment_date', '>=', $from);
    }

    /**
     * Scope a query to only return Payments which has payment_date
     * less or equal then given date
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param Date $to
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTo($query, $to)
    {
        $query->where('payment_date', '<=', $to);
    }

    /**
     * Scope a query to only return archived items 
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNonArchived($query)
    {
        $query->where('is_archived', 0);
    }

    /**
     * Delete payment
     *
     * @return void
     */
    public function deleteModel()
    {
        // Decrease paid amount from the invoice
        if ($this->invoice_id != null) {
            $invoice = Invoice::findOrFail($this->invoice_id);
            $invoice->due_amount = ((int)$invoice->due_amount + (int)$this->amount);

            // Set new Invoice paid_status
            if ($invoice->due_amount == $invoice->total) {
                $invoice->paid_status = Invoice::STATUS_UNPAID;
            } else {
                $invoice->paid_status = Invoice::STATUS_PARTIALLY_PAID;
            }

            // Save the Invoice
            $invoice->status = $invoice->getPreviousStatus();
            $invoice->save();
        }

        // Delete Payment from Database
        $this->delete();
    }
}
