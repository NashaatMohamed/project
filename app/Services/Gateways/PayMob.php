<?php

namespace App\Services\Gateways;

class PayMob
{
    protected $api_key;

    public function __construct()
    {
        $this->api_key = get_system_setting('paymob_api_key');
    }

    /**
     * Send POST cURL request to paymob servers.
     *
     * @param  string  $url
     * @param  array  $json
     * @return array
     */
    protected function cURL($url, $json)
    {
        // Create curl resource
        $ch = curl_init($url);

        // Request headers
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }

    /**
     * Send GET cURL request to paymob servers.
     *
     * @param  string  $url
     * @return array
     */
    protected function GETcURL($url)
    {
        // Create curl resource
        $ch = curl_init($url);

        // Request headers
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        // Return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $output contains the output string
        $output = curl_exec($ch);

        // Close curl resource to free up system resources
        curl_close($ch);
        return json_decode($output);
    }

    /**
     * Request auth token from paymob servers.
     *
     * @return object
     */
    public function auth()
    {
        // Request body
        $json = [
            'api_key' => $this->api_key,
        ];

        // Send curl
        $auth = $this->cURL(
            'https://accept.paymob.com/api/auth/tokens',
            $json
        );

        return $auth;
    }

    /**
     * Register order to paymob servers
     *
     * @param  string  $token
     * @param  int  $amount_cents
     * @param  string  $amount_cents
     * @param  int  $merchant_order_id
     * @return object
     */
    public function createOrder($token, $merchant_id, $amount_cents, $currency)
    {
        // Request body
        $json = [
            'merchant_id'            => $merchant_id,
            'amount_cents'           => $amount_cents,
            'currency'               => $currency,
            'notify_user_with_email' => true,
            'delivery_needed'        => false,
            'items'                  => [],
        ];

        // Send curl
        $order = $this->cURL(
            'https://accept.paymob.com/api/ecommerce/orders?token='.$token,
            $json
        );

        return $order;
    }

    /**
     * Get payment key to load iframe on paymob servers
     *
     * @param  string  $token
     * @param  int  $amount_cents
     * @param  int  $order_id
     * @param  string  $email
     * @param  string  $fname
     * @param  string  $lname
     * @param  int  $phone
     * @param  string  $city
     * @param  string  $country
     * @return array
     */
    public function createPaymentKey(
          $integration_id,
          $token,
          $amount_cents,
          $currency,
          $order_id,
          $email   = 'null',
          $fname   = 'null',
          $lname   = 'null',
          $phone   = 'null',
          $city    = 'null',
          $country = 'null'
      ) {
        // Request body
        $json = [
            'amount_cents' => $amount_cents,
            'expiration'   => 36000,
            'order_id'     => $order_id,
            "billing_data" => [
                "email"        => $email,
                "first_name"   => $fname,
                "last_name"    => $lname,
                "phone_number" => $phone,
                "city"         => $city,
                "country"      => $country,
                'street'       => 'null',
                'building'     => 'null',
                'floor'        => 'null',
                'apartment'    => 'null'
            ],
            'currency'            => $currency,
            'integration_id' => $integration_id
        ];

        // Send curl
        $payment_key = $this->cURL(
            'https://accept.paymob.com/api/acceptance/payment_keys?token='.$token,
            $json
        );

        return $payment_key;
    }

    /**
     * Create Mobile Wallet Payment Request
     *
     * @return object
     */
    public function createMobileWalletPayment($wallet_number, $token) 
    {
      // Request body
      $json = [
          "source" => [
              "identifier" => $wallet_number,
              "subtype"    => "WALLET",
          ],
          'payment_token' => $token
      ];

      // Send curl
      $payment_key = $this->cURL(
          'https://accept.paymob.com/api/acceptance/payments/pay',
          $json
      );

      return $payment_key;
    }

    /**
     * @param $amount
     */
    public function getServiceFee($amount, $payment_method)
    {
        switch ($payment_method) {
            case 'credit_card':
                $percent_fee = (float) number_format(get_system_setting('paymob_credit_card_percent_fee'), 2, '.', '');
                $online_payment_fixed_fee = (float) number_format(get_system_setting('paymob_credit_card_fixed_fee'), 2, '.', '') ?? 0;
                break;
            case 'valu':
                $percent_fee = (float) number_format(get_system_setting('paymob_valu_percent_fee'), 2, '.', '');
                $online_payment_fixed_fee = (float) number_format(get_system_setting('paymob_valu_fixed_fee'), 2, '.', '') ?? 0;
                break;
            case 'bank_installment':
                $percent_fee = (float) number_format(get_system_setting('paymob_bank_installment_percent_fee'), 2, '.', '');
                $online_payment_fixed_fee = (float) number_format(get_system_setting('paymob_bank_installment_fixed_fee'), 2, '.', '') ?? 0;
                break;
            case 'premium_card':
                $percent_fee = (float) number_format(get_system_setting('paymob_premium_card_percent_fee'), 2, '.', '');
                $online_payment_fixed_fee = (float) number_format(get_system_setting('paymob_premium_card_fixed_fee'), 2, '.', '') ?? 0;
                break;
            case 'mobile_wallet':
                $percent_fee = (float) number_format(get_system_setting('paymob_mobile_wallet_percent_fee'), 2, '.', '');
                $online_payment_fixed_fee = (float) number_format(get_system_setting('paymob_mobile_wallet_fixed_fee'), 2, '.', '') ?? 0;
                break;
        }

        $percent = 0;
        if ($percent_fee > 0) {
            $percent = ($amount * $percent_fee) / 100;
        }

        return $percent + ($online_payment_fixed_fee * 100);
    }
}
