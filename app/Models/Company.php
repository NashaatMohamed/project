<?php

namespace App\Models;

use App\Traits\HasAddresses;
use App\Traits\HasSubscriptions;
use App\Traits\UUIDTrait;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Traits\HasWallets;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model implements Wallet, WalletFloat
{
    use HasAddresses;
    use UUIDTrait;
    use HasSubscriptions;
    use HasWalletFloat, HasWallets;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'owner_id',
        'vat_number',
    ]; 
    
    /**
     * Define Relation with User Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('\App\Models\User', 'company_user', 'company_id', 'user_id')->withTimestamps();
    }

    /**
     * Define Relation with Addressable Model
     * This indicates the owner of the company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper function to determine if a user is part
     * of this company
     *
     * @param User $user
     * 
     * @return bool
     */
    public function hasUser(User $user)
    {
        return $this->users()->where($user->getKeyName(), $user->getKey())->first() ? true : false;
    }

    /**
     * Define Relation with CompanySetting Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany(CompanySetting::class);
    }

    /**
     * Define Relation with Vendor Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }

    /**
     * Define Relation with TaxType Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tax_types()
    {
        return $this->hasMany(TaxType::class);
    }

    /**
     * Define Relation with ProductUnit Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product_units()
    {
        return $this->hasMany(ProductUnit::class);
    }

    /**
     * Define Relation with Product Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products() : HasMany
    {
        return $this->hasMany(Product::class,"company_id");
    }

    public function product_variations() : HasMany
    {
        return $this->hasMany(ProductVariation::class,"company_id");
    }

    /**
     * Define Relation with PaymentMethod Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payment_methods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Define Relation with Payment Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Define Relation with Invoice Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Define Relation with InvoiceItem Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Define Relation with ExpenseCategory Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expense_categories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    /**
     * Define Relation with Expense Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Define Relation with Estimate Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function estimates()
    {
        return $this->hasMany(Estimate::class);
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
     * Define Relation with Customer Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Define Relation with CustomField Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function custom_fields()
    {
        return $this->hasMany(CustomField::class);
    }

    /**
     * Define Relation with CustomFieldValue Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function custom_field_values()
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    /**
     * Get Company Specified setting
     *
     * @param string $key
     * 
     * @return string
     */
    public function getSetting($key)
    {
        return CompanySetting::getSetting($key, $this->id);
    }

    /**
     * Set Company Specified setting
     *
     * @param string $key
     * @param string $value
     * 
     * @return void
     */
    public function setSetting($key, $value)
    {
        return CompanySetting::setSetting($key, $value, $this->id);
    }

    /**
     * Get Currency Attribute
     * 
     * @return string
     */
    public function getCurrencyAttribute($value)
    {
        return Currency::find($this->getSetting('currency_id'));
    }

    /**
     * Check if Paypal Gateway is Active
     * 
     * @return boolean
     */
    public function isPaypalActive()
    {   
        if (
            $this->getSetting('paypal_active') 
            && $this->getSetting('paypal_username') != ''
            && $this->getSetting('paypal_password') != ''
            && $this->getSetting('paypal_signature') != ''
        ) 
            return true;
        else 
            return false;
    }

    /**
     * Check if Stripe Gateway is Active
     * 
     * @return boolean
     */
    public function isStripeActive()
    {
        if (
            $this->getSetting('stripe_active') 
            && $this->getSetting('stripe_secret_key') != ''
            && $this->getSetting('stripe_public_key') != ''
        ) 
            return true;
        else 
            return false;
    }

    /**
     * Check if Razorpay Gateway is Active
     * 
     * @return boolean
     */
    public function isRazorpayActive()
    {
        if (
            $this->getSetting('razorpay_active') 
            && $this->getSetting('razorpay_id') != ''
            && $this->getSetting('razorpay_secret_key') != ''
        ) 
            return true;
        else 
            return false;
    }

    /**
     * Check if Mollie Gateway is Active
     * 
     * @return boolean
     */
    public function isMollieActive()
    {
        if (
            $this->getSetting('mollie_active') 
            && $this->getSetting('mollie_api_key') != ''
        ) 
            return true;
        else 
            return false;
    }

    /**
     * Get Commissions
     * 
     * @return array
     */
    public function getCommissions()
    {
        $subscription = $this->subscription('main');

        $withdraw_limit = null;
        $withdraw_fixed_fee = null;
        $withdraw_percent_fee = null;
        $online_payment_fixed_fee = null;
        $online_payment_percent_fee = null;

        if ($subscription) {
            $withdraw_limit = $this->getSetting('withdraw_limit') ?? $subscription->getFeatureValue('withdraw_limit');
            $withdraw_fixed_fee = $this->getSetting('withdraw_fixed_fee') ?? $subscription->getFeatureValue('withdraw_fixed_fee');
            $withdraw_percent_fee = $this->getSetting('withdraw_percent_fee') ?? $subscription->getFeatureValue('withdraw_percent_fee');
            $online_payment_fixed_fee = $this->getSetting('online_payment_fixed_fee') ?? $subscription->getFeatureValue('online_payment_fixed_fee');
            $online_payment_percent_fee = $this->getSetting('online_payment_percent_fee') ?? $subscription->getFeatureValue('online_payment_percent_fee');
        }

        return [
            'withdraw_limit' => $withdraw_limit,
            'withdraw_fixed_fee' => $withdraw_fixed_fee,
            'withdraw_percent_fee' => $withdraw_percent_fee,
            'online_payment_fixed_fee' => $online_payment_fixed_fee,
            'online_payment_percent_fee' => $online_payment_percent_fee,
        ];
    }

    /**
     * Return Default Company Avatar Url
     * 
     * @return string (url)
     */
    public function getDefaultAvatar()
    {
        return asset('assets/images/avatar/company.png');
    }

    /**
     * Get User's Company Url || Default Avatar
     * 
     * @return string (url)
     */
    public function getAvatarAttribute()
    {
        $avatar = CompanySetting::getSetting('avatar', $this->id);
        return $avatar ? asset($avatar) : $this->getDefaultAvatar();
    }
}
