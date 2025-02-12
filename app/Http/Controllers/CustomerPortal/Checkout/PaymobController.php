<?php

namespace App\Http\Controllers\CustomerPortal\Checkout;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Services\Gateways\PayMob;

class PayMobController extends BaseGatewayController
{
    /**
     * Display the Paymob Checkout Form
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $invoice = Invoice::findByUid($request->invoice);

        // Get Paymob Service
        $paymob = new PayMob();
        $auth = $paymob->auth();

        // Getting token has failed. Redirect back
        if (property_exists($auth, 'detail')) {
            session()->flash('alert-danger', __('messages.something_went_wrong'));
            return redirect()->back();
        }

        // Calculate gateway fee
        $fee = $paymob->getServiceFee($invoice->due_amount, $request->payment_method);
        $amount = $invoice->due_amount + $fee;

        // Create order at paymob
        $paymob_order = $paymob->createOrder(
            $auth->token,
            $auth->profile->id,
            $amount,
            $invoice->currency_code,
        );

        // Create payment key
        $payment_key = $paymob->createPaymentKey(
            $request->integration_id,
            $auth->token,
            $amount,
            $invoice->currency_code,
            $paymob_order->id,

            // For billing data
            $invoice->customer->email,
            'INV',
            $invoice->uid,
        );

        // Put redirect url to the user's session in order to return customer after the purchase
        session(['paymob_redirect_uri' => route('customer_portal.invoices.details', [
            'customer' => $request->customer, 
            'invoice' => $request->invoice
        ])]);

        return view('customer_portal.checkout.paymob', [
            'invoice' => $invoice,
            'payment_key' => $payment_key,
            'iframe_id' => $request->iframe_id,
            'total' => $amount, 
            'fee' => $fee,
        ]);
    }

    /**
     * Display the Paymob Mobile Wallet Payment
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function wallet_payment(Request $request)
    {
        // Get Paymob Service
        $paymob = new Paymob();

        // Put redirect url to the user's session in order to return customer after the purchase
        session(['paymob_redirect_uri' => route('customer_portal.invoices.details', [
            'customer' => $request->customer, 
            'invoice' => $request->invoice
        ])]);

        // Generate mobile wallet payment
        $mobile_wallet_payment = $paymob->createMobileWalletPayment($request->wallet_number, $request->token);

        // Redirect to wallet service
        if (property_exists($mobile_wallet_payment, 'redirect_url') && $mobile_wallet_payment->redirect_url != '') {
            return redirect()->to($mobile_wallet_payment->redirect_url);
        }

        // Getting token has failed. Redirect back
        session()->flash('alert-danger', __('messages.please_check_your_wallet_number'));
        return redirect()->back();
    }
}
