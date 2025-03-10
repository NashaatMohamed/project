<?php

namespace App\Http\Controllers\CustomerPortal\Checkout;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Services\Gateways\PaypalExpress;

class PaypalExpressController extends BaseGatewayController

{
    /**
     * Create the Payment Request
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function payment(Request $request)
    {
        $invoice = Invoice::findByUid($request->invoice);
 
        // Get PaypalExpress Service
        $paypal = new PaypalExpress($invoice->company, true);

        // Calculate gateway fee
        $fee = $paypal->getServiceFee($invoice->due_amount);
        $amount = $invoice->due_amount + $fee;
 
        // Make the Payment Request
        $response = $paypal->purchase([
            'amount' => $paypal->formatAmount($amount),
            'transactionId' => $invoice->uid,
            'currency' => $invoice->currency_code,
            'cancelUrl' => $paypal->getCancelUrl($invoice),
            'returnUrl' => $paypal->getReturnUrl($invoice),
        ]);

        // Redirect customer to Paypal website
        if ($response->isRedirect()) {
            $response->redirect();
        }
 
        // Something else happend, go back to invoice details
        session()->flash('alert-danger', $response->getMessage());
        return redirect()->route('customer_portal.invoices.details', [
            'customer' => $request->customer, 
            'invoice' => $request->invoice
        ]);
    }

    /**
     * Complete the Payment Request
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function completed(Request $request)
    {
        $invoice = Invoice::findByUid($request->invoice);

        // Get PaypalExpress Service
        $paypal = new PaypalExpress($invoice->company, true);

        // Calculate gateway fee
        $fee = $paypal->getServiceFee($invoice->due_amount);
        $amount = $invoice->due_amount + $fee;

        // Complete the Payment Request
        $response = $paypal->complete([
            'amount' => $paypal->formatAmount($amount),
            'transactionId' => $invoice->uid,
            'currency' => $invoice->currency_code,
            'cancelUrl' => $paypal->getCancelUrl($invoice),
            'returnUrl' => $paypal->getReturnUrl($invoice),
        ]);
 
        // If payment was successful then save payment and return user to success page
        if ($response->isSuccessful()) {
            // Create and Save Payment to Database
            $payment = $this->savePayment($invoice, 'Paypal', $response->getTransactionReference());

            session()->flash('alert-success', __('messages.payment_successful', ['payment_number' => $payment->payment_number]));
            return redirect()->route('customer_portal.invoices.details', [
                'customer' => $request->customer, 
                'invoice' => $request->invoice
            ]);
        }

        // Something else happend, go back to invoice details
        session()->flash('alert-danger', $response->getMessage());
        return redirect()->route('customer_portal.invoices.details', [
            'customer' => $request->customer, 
            'invoice' => $request->invoice
        ]);
    }

    /**
     * Cancel the Payment Request
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function cancelled(Request $request)
    {
        session()->flash('alert-danger', __('messages.payment_cancelled_paypal'));
        return redirect()->route('customer_portal.invoices.details', [
            'customer' => $request->customer, 
            'invoice' => $request->invoice
        ]);
    }
}