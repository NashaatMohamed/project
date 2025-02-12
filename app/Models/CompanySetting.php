<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'option',
        'value'
    ];

    /**
     * Default Company Settings
     *
     * @var array
     */
    public static function getDefaultSettings() {
        return [
            'language' => env('CS_LANGUAGE', 'en'),
            'date_format' => env('CS_DATE_FORMAT', 'Y M d'),
            'timezone' => env('CS_TIMEZONE', 'Europe/London'),
            'currency_id' => env('CS_CURRENCY_ID', 43),
            'financial_month_starts' => env('CS_FINANCIAL_MONTH_STARTS', '1'),
            'financial_month_ends' => env('CS_FINANCIAL_MONTH_ENDS', '12'),
            'invoice_prefix' => env('CS_INVOICE_PREFIX', ''),
            'credit_note_prefix' => env('CS_CREDIT_NOTE_PREFIX', 'CN'),
            'estimate_prefix' => env('CS_ESTIMATE_PREFIX', 'EST'),
            'payment_prefix' => env('CS_PAYMENT_PREFIX', 'PAY'),
            'tax_per_item' => env('CS_TAX_PER_ITEM', false),
            'discount_per_item' => env('CS_DISCOUNT_PER_ITEM', false),
            'invoice_color' => env('CS_INVOICE_COLOR', '#308AF3'),
            'invoice_auto_archive' => env('CS_INVOICE_AUTO_ARCHIVE', false),
            'invoice_footer' => env('CS_INVOICE_FOOTER', ''),
            'invoice_due_reminder_1_before_days' => env('CS_INVOICE_DUE_REMINDER_1', null),
            'invoice_due_reminder_2_before_days' => env('CS_INVOICE_DUE_REMINDER_2', null),
            'invoice_overdue_reminder_1_after_days' => env('CS_INVOICE_OVERDUE_REMINDER_1', null),
            'invoice_overdue_reminder_2_after_days' => env('CS_INVOICE_OVERDUE_REMINDER_2', null),
            'invoice_show_payments_on_pdf' => env('CS_INVOICE_SHOW_PAYMENT_ON_PDF', true),
            'estimate_color' => env('CS_ESTIMATE_COLOR', '#308AF3'),
            'estimate_footer' => env('CS_ESTIMATE_FOOTER', ''),
            'estimate_auto_archive' => env('CS_ESTIMATE_AUTO_ARCHIVE', false),
            'estimate_auto_convert' => env('CS_ESTIMATE_AUTO_CONVERT', false),
            'payment_color' => env('CS_PAYMENT_COLOR', '#308AF3'),
            'payment_footer' => env('CS_PAYMENT_FOOTER', ''),
            'payment_auto_archive' => env('CS_PAYMENT_AUTO_ARCHIVE', false),
            'invoice_mail_subject' => env('CS_INVOICE_MAIL_SUBJECT', 'Invoice {invoice.number} from {company.name}'),
            'invoice_mail_content' => env('CS_INVOICE_MAIL_CONTENT', '<p>Dear {customer.display_name},</p><p><br></p><p>Please find the attached invoice from the link below. We appreciate your prompt payment.</p><p><br></p><p>{invoice.link}</p><p><br></p><p>If you have any question, feel free to contact us. </p><p><br></p><p>Thank you,</p><p>{company.name}.</p>'),
            'invoice_due_mail_subject' => env('CS_INVOICE_DUE_MAIL_SUBJECT', 'Your {invoice.number} will be due soon from {company.name}'),
            'invoice_due_mail_content' => env('CS_INVOICE_DUE_MAIL_CONTENT', '<p>Dear {customer.display_name},</p><p><br></p><p>You invoice <strong>{invoice.number} </strong>will be due on <strong>{invoice.due_date}</strong></p><p><br></p><p>You can view the invoice on the following link: {invoice.link}</p><p><br></p><p>Thank you,</p><p>{company.name}.</p>'),
            'invoice_overdue_mail_subject' => env('CS_INVOICE_OVERDUE_MAIL_SUBJECT', '{invoice.number} Invoice Overdue Reminder from {company.name}'),
            'invoice_overdue_mail_content' => env('CS_INVOICE_OVERDUE_MAIL_CONTENT', '<p>Dear {customer.display_name},</p><p><br></p><p>This is an overdue notice for invoice <strong>{invoice.number}</strong></p><p><br></p><p>This invoice was due: <strong>{invoice.due_date}</strong></p><p><br></p><p>You can view the invoice on the following link: {invoice.link}</p><p><br></p><p>If you have any question, feel free to contact us.</p><p><br></p><p>Thank you,</p><p>{company.name}.</p>'),
            'estimate_mail_subject' => env('CS_ESTIMATE_MAIL_SUBJECT', 'Estimate {estimate.number} from {company.name}'),
            'estimate_mail_content' => env('CS_ESTIMATE_MAIL_CONTENT', '<p>Dear {customer.display_name},</p><p><br></p><p>Please find the attached estimate from the link below.</p><p><br></p><p>{estimate.link}</p><p><br></p><p>If you have any question, feel free to contact us. </p><p><br></p><p>Thank you,</p><p>{company.name}.</p>'),
            'payment_mail_subject' => env('CS_PAYMENT_MAIL_SUBJECT', 'Payment Receipt {payment.number} from {company.name}'),
            'payment_mail_content' => env('CS_PAYMENT_MAIL_CONTENT', '<p>Dear {customer.display_name},</p><p><br></p><p>Thank you for the payment. </p><p>Please find the attached payment receipt from the link below.</p><p><br></p><p>{payment.link}</p><p><br></p><p>If you have any question, feel free to contact us. </p><p><br></p><p>Thank you,</p><p>{company.name}.</p>'),
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
            'avatar' => null,
            'invoice_template' => env('CS_INVOICE_TEMPLATE', 'template_1'),
            'credit_note_template' => env('CS_CREDIT_NOTE_TEMPLATE', 'template_1'),
            'estimate_template' => env('CS_ESTIMATE_TEMPLATE', 'template_1'),
            'payment_template' => env('CS_PAYMENT_TEMPLATE', 'template_1'),
            'mollie_api_key' => '',
            'mollie_test_mode' => false,
            'mollie_active' => false,
            'invoice_from_template' => env('CS_INVOICE_FROM_TEMPLATE', '<p class="mb-0"><strong>{company.name}</strong></p><p class="mb-0">{company.billing.address_1}</p><p class="mb-0">{company.billing.address_2}</p><p class="mb-0">{company.billing.city}, {company.billing.state}</p><p class="mb-0">{company.billing.country}</p><p class="mb-0">{company.billing.phone}</p><p class="mb-0">VAT: {company.vat_number}</p>'),
            'invoice_to_template' => env('CS_INVOICE_TO_TEMPLATE', '<p class="mb-0"><strong>{customer.name}</strong></p><p class="mb-0">{customer.billing.address_1}</p><p class="mb-0">{customer.billing.address_2}</p><p class="mb-0">{customer.billing.city}, {customer.billing.state}</p><p class="mb-0">{customer.billing.country}</p><p class="mb-0">{customer.billing.phone}</p><p class="mb-0">VAT: {customer.vat_number}</p>'),
            'invoice_ships_to_template' => env('CS_INVOICE_SHIPS_TO_TEMPLATE', '<p class="mb-0">{customer.shipping.address_1}</p><p class="mb-0">{customer.shipping.address_2}</p><p class="mb-0">{customer.shipping.city}, {customer.shipping.state}</p><p class="mb-0">{customer.shipping.country}</p><p class="mb-0">{customer.shipping.phone}</p>'),
            'estimate_from_template' => env('CS_ESTIMATE_FROM_TEMPLATE', '<p class="mb-0"><strong>{company.name}</strong></p><p class="mb-0">{company.billing.address_1}</p><p class="mb-0">{company.billing.address_2}</p><p class="mb-0">{company.billing.city}, {company.billing.state}</p><p class="mb-0">{company.billing.country}</p><p class="mb-0">{company.billing.phone}</p><p class="mb-0">VAT: {company.vat_number}</p>'),
            'estimate_to_template' => env('CS_ESTIMATE_TO_TEMPLATE', '<p class="mb-0"><strong>{customer.name}</strong></p><p class="mb-0">{customer.billing.address_1}</p><p class="mb-0">{customer.billing.address_2}</p><p class="mb-0">{customer.billing.city}, {customer.billing.state}</p><p class="mb-0">{customer.billing.country}</p><p class="mb-0">{customer.billing.phone}</p><p class="mb-0">VAT: {customer.vat_number}</p>'),
            'estimate_ships_to_template' => env('CS_ESTIMATE_SHIPS_TO_TEMPLATE', '<p class="mb-0">{customer.shipping.address_1}</p><p class="mb-0">{customer.shipping.address_2}</p><p class="mb-0">{customer.shipping.city}, {customer.shipping.state}</p><p class="mb-0">{customer.shipping.country}</p><p class="mb-0">{customer.shipping.phone}</p>'),
            'payment_from_template' => env('CS_ESTIMATE_FROM_TEMPLATE', '<p class="mb-0"><strong>{company.name}</strong></p><p class="mb-0">{company.billing.address_1}</p><p class="mb-0">{company.billing.address_2}</p><p class="mb-0">{company.billing.city}, {company.billing.state}</p><p class="mb-0">{company.billing.country}</p><p class="mb-0">{company.billing.phone}</p><p class="mb-0">VAT: {company.vat_number}</p>'),
            'payment_to_template' => env('CS_ESTIMATE_TO_TEMPLATE', '<p class="mb-0"><strong>{customer.name}</strong></p><p class="mb-0">{customer.billing.address_1}</p><p class="mb-0">{customer.billing.address_2}</p><p class="mb-0">{customer.billing.city}, {customer.billing.state}</p><p class="mb-0">{customer.billing.country}</p><p class="mb-0">{customer.billing.phone}</p><p class="mb-0">VAT: {customer.vat_number}</p>'),
            'payment_ships_to_template' => env('CS_ESTIMATE_SHIPS_TO_TEMPLATE', '<p class="mb-0">{customer.shipping.address_1}</p><p class="mb-0">{customer.shipping.address_2}</p><p class="mb-0">{customer.shipping.city}, {customer.shipping.state}</p><p class="mb-0">{customer.shipping.country}</p><p class="mb-0">{customer.shipping.phone}</p>'),
            'discount_type' => env('CS_DISCOUNT_TYPE', '1'),
            'credit_note_color' => env('CS_CREDIT_NOTE_COLOR', '#308AF3'),
            'credit_note_from_template' => env('CS_CREDIT_NOTE_FROM_TEMPLATE', '<p class="mb-0"><strong>{company.name}</strong></p><p class="mb-0">{company.billing.address_1}</p><p class="mb-0">{company.billing.address_2}</p><p class="mb-0">{company.billing.city}, {company.billing.state}</p><p class="mb-0">{company.billing.country}</p><p class="mb-0">{company.billing.phone}</p><p class="mb-0">VAT: {company.vat_number}</p>'),
            'credit_note_to_template' => env('CS_CREDIT_NOTE_TO_TEMPLATE', '<p class="mb-0"><strong>{customer.name}</strong></p><p class="mb-0">{customer.billing.address_1}</p><p class="mb-0">{customer.billing.address_2}</p><p class="mb-0">{customer.billing.city}, {customer.billing.state}</p><p class="mb-0">{customer.billing.country}</p><p class="mb-0">{customer.billing.phone}</p><p class="mb-0">VAT: {customer.vat_number}</p>'),
            'credit_note_ships_to_template' => env('CS_CREDIT_NOTE_SHIPS_TO_TEMPLATE', '<p class="mb-0">{customer.shipping.address_1}</p><p class="mb-0">{customer.shipping.address_2}</p><p class="mb-0">{customer.shipping.city}, {customer.shipping.state}</p><p class="mb-0">{customer.shipping.country}</p><p class="mb-0">{customer.shipping.phone}</p>'),
            'credit_note_footer' => env('CS_INVOICE_FOOTER', ''),
            'credit_note_mail_subject' => env('CS_CREDIT_NOTE_MAIL_SUBJECT', 'Credit Note {credit_note.number} from {company.name}'),
            'credit_note_mail_content' => env('CS_CREDIT_NOTE_MAIL_CONTENT', '<p>Dear {customer.display_name},</p><p><br></p><p>Please find the attached credit note from the link below.</p><p><br></p><p>{credit_note.link}</p><p><br></p><p>If you have any question, feel free to contact us. </p><p><br></p><p>Thank you,</p><p>{company.name}.</p>'),
            'withdraw_limit' => null,
            'withdraw_fixed_fee' => null,
            'withdraw_percent_fee' => null,
            'online_payment_fixed_fee' => null,
            'online_payment_percent_fee' => null,
        ];
    }

    /**
     * Define Relation with User Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Set new or update existing Company Settings.
     *
     * @param string $key
     * @param string $setting
     * @param string $company_id
     *
     * @return void
     */
    public static function setSetting($key, $setting, $company_id): void
    {
        $old = self::whereOption($key)->findByCompany($company_id)->first();

        if ($old) {
            $old->value = $setting;
            $old->save();
            return;
        }

        $set = new CompanySetting();
        $set->option = $key;
        $set->value = $setting;
        $set->company_id = $company_id;
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
        $defaultSettings = self::getDefaultSettings();
        $setting = $defaultSettings[$key];

        if ($setting) {
            return $setting;
        } else {
            return null;
        }
    }

    /**
     * Get Company Setting.
     *
     * @param string $key
     * @param string $company_id
     *
     * @return string|null
     */
    public static function getSetting($key, $company_id)
    {
        $setting = static::whereOption($key)->findByCompany($company_id)->first();

        if ($setting) {
            return $setting->value;
        } else {
            return self::getDefaultSetting($key);
        }
    }

    /**
     * Scope a query to only include settings of a given company.
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