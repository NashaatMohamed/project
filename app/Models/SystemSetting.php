<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'option',
        'value'
    ];

    /**
     * Default Company Settings
     *
     * @var array
     */
    public static $defaultSettings = [
        'application_name' => 'Foxtrot',
        'application_logo' => '/assets/images/foxtrot-black.png',
        'application_favicon' => '/assets/images/fox-logo-black.svg',
        'application_currency' => 'EGP',
        'meta_description' => 'Foxtrot - Customer, Invoice and Expense Management System',
        'meta_keywords' => 'accounting, billing, business management, client management',
        'theme' => 'bikin',
        'paypal_username' => '',
        'paypal_password' => '',
        'paypal_signature' => '',
        'paypal_test_mode' => false,
        'paypal_active' => false,
        'stripe_public_key' => '',
        'stripe_secret_key' => '',
        'stripe_test_mode' => false,
        'stripe_active' => false,
        'razorpay_id' => '',
        'razorpay_secret_key' => '',
        'razorpay_test_mode' => false,
        'razorpay_active' => false,
        'version' => '1.0.0',
        'mollie_api_key' => '',
        'mollie_test_mode' => false,
        'mollie_active' => false,
        
        // Paymob
        'paymob_api_key' => null,
        'paymob_credit_card_integration_id' => null,
        'paymob_credit_card_iframe_id' => null,
        'paymob_valu_integration_id' => null,
        'paymob_valu_iframe_id' => null,
        'paymob_premium_card_integration_id' => null,
        'paymob_premium_card_iframe_id' => null,
        'paymob_bank_installment_integration_id' => null,
        'paymob_bank_installment_iframe_id' => null,
        'paymob_mobile_wallet_integration_id' => null,
        'paymob_active' => false,
        
        'google_recapthca_key' => '',
        'recurring_invoice_cycle' => 13,
        'expiring_subscription_overdue_mail_subject' => 'Your subscription is expired!',
        'expiring_subscription_overdue_mail_content' => '<p>Please update your payment settings in order to continue our platform.</p><br><br>',
        'expiring_subscription_overdue_after_x_days' => 3,
        'expiring_subscription_due_mail_subject' => 'Your subscription is expiring!',
        'expiring_subscription_due_mail_content' => '<p>Please update your payment settings in order to continue our platform.</p><br><br>',
        'expiring_subscription_due_before_x_days' => 2,
        'custom_css' => '',
        'custom_js' => '',
        'recurring_expense_cycle' => 13,

        'stripe_fixed_fee' => null,
        'stripe_percent_fee' => null,
        'paypal_fixed_fee' => null,
        'paypal_percent_fee' => null,
        'razorpay_fixed_fee' => null,
        'razorpay_percent_fee' => null,
        'mollie_fixed_fee' => null,
        'mollie_percent_fee' => null,
        'paymob_credit_card_percent_fee' => null,
        'paymob_credit_card_fixed_fee' => null,
        'paymob_valu_percent_fee' => null,
        'paymob_valu_fixed_fee' => null,
        'paymob_bank_installment_percent_fee' => null,
        'paymob_bank_installment_fixed_fee' => null,
        'paymob_premium_card_percent_fee' => null,
        'paymob_premium_card_fixed_fee' => null,
        'paymob_mobile_wallet_percent_fee' => null,
        'paymob_mobile_wallet_fixed_fee' => null,
    ];

    /**
     * Set new or update existing System Settings.
     *
     * @param string $key
     * @param string $setting
     *
     * @return void
     */
    public static function setSetting($key, $setting)
    {
        $old = self::whereOption($key)->first();

        if ($old) {
            $old->value = $setting;
            $old->save();
            return;
        }

        $set = new SystemSetting();
        $set->option = $key;
        $set->value = $setting;
        $set->save();
    }
 
    /**
     * Get Default Company Setting.
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function getDefaultSetting($key)
    {
        $setting = self::$defaultSettings[$key];

        if ($setting) {
            return $setting;
        } else {
            return null;
        }
    }

    /**
     * Get System Setting.
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function getSetting($key)
    {
        $setting = static::whereOption($key)->first();

        if ($setting) {
            return $setting->value;
        } else {
            return self::getDefaultSetting($key);
        }
    }

    /**
     * Check if Paymob Gateway is Active
     *
     * @return boolean
     */
    public static function isPaymobActive()
    {
        if (
            static::getSetting('paymob_active') &&
            (
                static::isPaymobCreditCardActive() ||
                static::isPaymobValUActive() ||
                static::isPaymobPremiumCardActive() ||
                static::isPaymobBankInstallmentActive() ||
                static::isPaymobMobileWalletActive()
            )
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if Paymob Credit Card Gateway is Active
     *
     * @return boolean
     */
    public static function isPaymobCreditCardActive()
    {
        if (
            static::getSetting('paymob_active') &&
            static::getSetting('paymob_credit_card_integration_id') != '' && 
            static::getSetting('paymob_credit_card_iframe_id') != ''
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if Paymob ValU Gateway is Active
     *
     * @return boolean
     */
    public static function isPaymobValUActive()
    {
        if (
            static::getSetting('paymob_active') &&
            static::getSetting('paymob_valu_integration_id') != '' && 
            static::getSetting('paymob_valu_iframe_id') != ''
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if Paymob Premium Card Gateway is Active
     *
     * @return boolean
     */
    public static function isPaymobPremiumCardActive()
    {
        if (
            static::getSetting('paymob_active') &&
            static::getSetting('paymob_premium_card_integration_id') != '' && 
            static::getSetting('paymob_premium_card_iframe_id') != ''
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if Paymob Bank Installment Gateway is Active
     *
     * @return boolean
     */
    public static function isPaymobBankInstallmentActive()
    {
        if (
            static::getSetting('paymob_active') &&
            static::getSetting('paymob_bank_installment_integration_id') != '' && 
            static::getSetting('paymob_bank_installment_iframe_id') != ''
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if Paymob Mobile Wallet Gateway is Active
     *
     * @return boolean
     */
    public static function isPaymobMobileWalletActive()
    {
        if (
            static::getSetting('paymob_active') &&
            static::getSetting('paymob_mobile_wallet_integration_id') != ''
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if Paypal Gateway is Active
     *
     * @return boolean
     */
    public static function isPaypalActive()
    {
        if (
            static::getSetting('paypal_active')
            && static::getSetting('paypal_username') != ''
            && static::getSetting('paypal_password') != ''
            && static::getSetting('paypal_signature') != ''
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if Stripe Gateway is Active
     *
     * @return boolean
     */
    public static function isStripeActive()
    {
        if (
            static::getSetting('stripe_active')
            && static::getSetting('stripe_secret_key') != ''
            && static::getSetting('stripe_public_key') != ''
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Check if Razorpay Gateway is Active
    *
    * @return boolean
    */
    public static function isRazorpayActive()
    {
        if (
            static::getSetting('razorpay_active')
            && static::getSetting('razorpay_id') != ''
            && static::getSetting('razorpay_secret_key') != ''
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if Mollie Gateway is Active
     * 
     * @return boolean
     */
    public static function isMollieActive()
    {
        if (
            static::getSetting('mollie_active') 
            && static::getSetting('mollie_api_key') != ''
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
    public static function isTermsActive()
    {
        $terms = Page::findBySlug('terms');
        $privacy = Page::findBySlug('privacy');

        if (!$terms || !$privacy)
            return false;

        if ($terms->is_active && $privacy->is_active) 
            return true;
        else 
            return false;
    }

    // Save Settings on .env file
    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        $str .= "\n";

        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }
}
