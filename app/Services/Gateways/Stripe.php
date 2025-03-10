<?php

namespace App\Services\Gateways;

use Omnipay\Omnipay;

class Stripe
{
    public $company;

    /**
     * PaypalExpress Construct
     */
    function __construct($company, $saas = false)
    {
        $this->saas = $saas;
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function gateway()
    {
        $gateway = Omnipay::create('Stripe\PaymentIntents');

        if ($this->saas) {
            $gateway->setApiKey(get_system_setting('stripe_secret_key'));
            $gateway->setTestMode(get_system_setting('stripe_test_mode'));

            return $gateway;
        }

        $gateway->setApiKey($this->company->getSetting('stripe_secret_key'));
        $gateway->setTestMode($this->company->getSetting('stripe_test_mode'));
 
        return $gateway;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function purchase(array $parameters)
    {
        $response = $this->gateway()
            ->purchase($parameters)
            ->send();

        return $response;
    }

    /**
     * @param array $parameters
     */
    public function complete(array $parameters)
    {
        $response = $this->gateway()
            ->confirm($parameters)
            ->send();

        return $response;
    }

    /**
     * @param $amount
     */
    public function getServiceFee($amount)
    {
        $percent = 0;
        $percent_fee = (float) number_format(get_system_setting('stripe_percent_fee'), 2, '.', '');
        if ($percent_fee > 0) {
            $percent = ($amount * $percent_fee) / 100;
        }
        $online_payment_fixed_fee = (float) number_format(get_system_setting('stripe_fixed_fee'), 2, '.', '') ?? 0;
        return $percent + ($online_payment_fixed_fee * 100);
    }

    /**
     * @param $amount
     */
    public function formatAmount($amount)
    {
        return number_format($amount/100, 2, '.', '');
    }

    /**
     * @param $invoice
     */
    public function getReturnUrl($invoice)
    {
        return route('customer_portal.invoices.stripe.completed', [
            'customer' => $invoice->customer->uid ,
            'invoice' => $invoice->uid
        ]);
    }
}
