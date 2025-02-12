<?php

namespace App\Models;

use App\Traits\CustomPDFFields;
use App\Traits\HasCustomFields;
use App\Traits\HasTax;
use App\Traits\UUIDTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    use UUIDTrait;
    use HasTax;
    use HasCustomFields;
    use CustomPDFFields;

    const STATUS_DRAFT = 'DRAFT';
    const STATUS_SENT = 'SENT';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'customer_id',
        'credit_note_date',
        'credit_note_number',
        'reference_number',
        'tax_per_item',
        'discount_per_item',
        'status',
        'notes',
        'private_notes',
        'discount_type',
        'discount_val',
        'sub_total',
        'total',
        'is_archived',
        'template_id',
    ];

    /**
     * Automatically cast date attributes to Carbon
     * 
     * @var array
     */
    protected $dates = [
        'created_at', 
        'updated_at', 
        'credit_note_date'
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'sub_total' => 'integer',
        'total' => 'integer',
        'discount_val' => 'integer',
        'tax_per_item' => 'boolean',
        'discount_per_item' => 'boolean',
        'is_archived' => 'boolean',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['fields'];

    /**
     * Define Relation with CreditNoteItem Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(CreditNoteItem::class);
    }

    /**
     * Define Relation with Credit Note Refund Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refunds()
    {
        return $this->hasMany(CreditNoteRefund::class);
    }

    /**
     * Define Relation with Payment Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applied_payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Define Relation with Credit Note Template Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(CreditNoteTemplate::class);
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
     * Define Relation with Company Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the Total Percentage of Taxes
     * 
     * @return int
     */
    public function getTotalPercentageOfTaxes()
    {
        $total = 0;
        foreach ($this->taxes as $tax) {
            $total += $tax->tax_type->percent;
        }

        return (int) $total;
    }

    /**
     * Get the Total Percentage of Taxes with Tax Names
     * 
     * @return array 
     */
    public function getTotalPercentageOfTaxesWithNames()
    {
        $total = [];
        foreach ($this->taxes as $tax) {
            if (isset($total[$tax->tax_type->name])) {
                $total[$tax->tax_type->name] += $tax->tax_type->percent;
            } else {
                $total[$tax->tax_type->name] = $tax->tax_type->percent;
            }
        }

        return $total;
    }

    /**
     * Get the Items Sub Total by base price
     * 
     * @return array 
     */
    public function getItemsSubTotalByBasePrice()
    {
        $sub_total = 0;
        foreach ($this->items as $item) {
            $sub_total += $item->price;
        }

        return $sub_total;
    }

    /**
     * Get the Total Percentage of Invoice Items Taxes with Tax Names
     * 
     * @return array 
     */
    public function getItemsTotalPercentageOfTaxesWithNames()
    {
        $total = [];
        foreach ($this->items as $item) {
            foreach ($item->taxes as $tax) {
                if (isset($total[$tax->tax_type->name . ' (' . $tax->tax_type->percent . '%)'])) {
                    $total[$tax->tax_type->name . ' (' . $tax->tax_type->percent . '%)'] += ($tax->tax_type->percent / 100) * $item->price;
                } else {
                    $total[$tax->tax_type->name . ' (' . $tax->tax_type->percent . '%)'] = ($tax->tax_type->percent / 100) * $item->price;
                }
            }
        }
        return $total;
    }

    /**
     * Get the Total Percentage of Invoice Items Taxes with Tax Names
     * 
     * @return array 
     */
    public function getItemsTotalDiscount()
    {
        $discount_amount = 0;
        foreach ($this->items as $item) {
            $price = $item->price;
            foreach ($item->taxes as $tax) {
                $price += ($tax->tax_type->percent / 100) * $item->price;
            }
            $discount_amount += ($item->discount_val / 100) * $price;
        }
        return $discount_amount;
    }

    /**
     * Customized strpos helper function for excluding prefix 
     * from credit note number
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
     * Helper function for getting the next Credit Note Number
     * by searching the database and increase 1
     * 
     * @param string $prefix
     * 
     * @return string
     */
    public static function getNextCreditNoteNumber($company_id, $prefix)
    {
        // Get the last created order
        $lastOrder = CreditNote::findByCompany($company_id)->where('credit_note_number', 'LIKE', $prefix . '-%')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastOrder) {
            // We get here if there is no order at all
            // If there is no number set it to 0, which will be 1 at the end.
            $number = 0;
        } else {
            $number = explode("-", $lastOrder->credit_note_number);
            $number = $number[1];
        }

        // If we have EST-000001 in the database then we only want the number
        // So the substr returns this 000001

        // Add the string in front and higher up the number.
        // the %05d part makes sure that there are always 6 numbers in the string.
        // so it adds the missing zero's when needed.

        return sprintf('%06d', intval($number) + 1);
    }

    /**
     * Set credit_note_num attribute
     *
     * @return int
     */
    public function getCreditNoteNumAttribute()
    {
        $position = $this->strposX($this->credit_note_number, "-", 1) + 1;
        return substr($this->credit_note_number, $position);
    }

    /**
     * Set credit_note_prefix attribute
     * 
     * @return string
     */
    public function getCreditNotePrefixAttribute ()
    {
        return $this->id 
            ? explode("-", $this->credit_note_number)[0]
            : CompanySetting::getSetting('credit_note_prefix', $this->company_id);
    }

     /**
     * Calculate the remaining balance
     * 
     * @return integer 
     */
    public function getRemainingBalanceAttribute()
    { 
        // Get all refunds
        $total_refund_amount = (int) $this->refunds()->sum('amount');

        // Get all applied invoices
        $total_applied_amount = (int) $this->applied_payments()->sum('amount');

        return $this->total - ($total_refund_amount + $total_applied_amount);
    }

    /**
     * Set currency attribute from customer
     *
     * @return App\Model\Currency
     */
    public function getCurrencyAttribute($value)
    {
        return $this->customer->currency;
    }

    /**
     * Set currency_code attribute from customer
     *
     * @return string
     */
    public function getCurrencyCodeAttribute($value)
    {
        return $this->customer->currency->short_code;
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
     * Set formatted_expiry_date attribute by custom date format
     * from Company Settings
     *
     * @return string
     */
    public function getFormattedExpiryDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('date_format', $this->company_id);
        return Carbon::parse($this->expiry_date)->format($dateFormat);
    }

    /**
     * Set formatted_credit_note_date attribute by custom date format
     * from Company Settings
     *
     * @return string
     */
    public function getFormattedCreditNoteDateAttribute($value)
    {
        $dateFormat = CompanySetting::getSetting('date_format', $this->company_id);
        return Carbon::parse($this->credit_note_date)->format($dateFormat);
    }

    /**
     * Set display_name attribute
     * 
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->credit_note_number;
    }

    /**
     * Set template attribute
     *
     * @return string
     */
    public function getTemplateViewAttribute()
    {
        return $this->template_id ? $this->template->view : $this->company->getSetting('credit_note_template');
    }

    /**
     * Scope a query to only include CreditNotes of a given company.
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
     * Scope a query to only return draft CreditNotes
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDrafts($query)
    {
        $query->where('status', self::STATUS_DRAFT);
    }
    
    /**
     * Scope a query to only return active CreditNotes
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        $active_stats = [
            self::STATUS_SENT,
        ];
        $query->whereIn('status', $active_stats);
    }

    /**
     * Scope a query to only return non draft CreditNotes
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNonDraft($query)
    {
        $query->where('status', '!=', self::STATUS_DRAFT);
    }

    /**
     * Scope a query to only return CreditNotes which has credit_note_date
     * greater or equal then given date
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param Date $from
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFrom($query, $from)
    {
        $query->where('credit_note_date', '>=', $from);
    }

    /**
     * Scope a query to only return CreditNotes which has credit_note_date
     * less or equal then given date
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param Date $to
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTo($query, $to)
    {
        $query->where('credit_note_date', '<=', $to);
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
}
