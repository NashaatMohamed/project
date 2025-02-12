<?php

namespace App\Traits;

use App\Events\InvoicePaidEvent;
use App\Mails\PaymentReceiptToCustomer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Mail;

trait SavesInvoicePayment
{
    /**
     * Helper function to
     * Save Payment to Database
     * 
     * @param \App\Models\Invoice $invoice
     * @param string $gateway
     * @param string $reference
     * 
     * @return \App\Models\Payment
     */
    public function savePayment($invoice, $gateway, $reference)
    { 
        // Company
        $currentCompany = $invoice->company;
        $commissions = $currentCompany->getCommissions();

        // Set payment_number
        $payment_prefix = $invoice->company->getSetting('payment_prefix');
        $next_payment_number = Payment::getNextPaymentNumber($currentCompany->id, $payment_prefix);
        $payment_number_with_prefix = $payment_prefix.'-'.sprintf('%06d', intval($next_payment_number));

        // Find or Create Payment Method
        $method = PaymentMethod::firstOrCreate(['name' => $gateway]);

        // Create Payment and Store in Database 
        $payment = Payment::create([
            'payment_date' => now()->format('Y-m-d'),
            'payment_number' => $payment_number_with_prefix,
            'customer_id' => $invoice->customer->id,
            'company_id' => $invoice->company->id,
            'invoice_id' => $invoice->id,
            'payment_method_id' => $method->id,
            'transaction_reference' => $reference,
            'amount' => $invoice->due_amount,
        ]);

        // Find or create wallet to deposit
        $wallet = $currentCompany->getWallet($invoice->currency_code);
        if (!$wallet) {
            $wallet = $currentCompany->createWallet([
                'name' => $invoice->currency_code,
                'slug' => strtolower($invoice->currency_code),
            ]);
        }

        // Calculate withdraw commissions
        $percent = 0;
        $online_payment_percent_fee = (float) number_format($commissions['online_payment_percent_fee'], 2, '.', '');
        if ($online_payment_percent_fee > 0) {
            $percent = ($invoice->due_amount * $online_payment_percent_fee) / 100;
        }
        $online_payment_fixed_fee = (float) number_format($commissions['online_payment_fixed_fee'], 2, '.', '') ?? 0;
        $total_commission = $percent + ($online_payment_fixed_fee * 100);

        // Deposit amount
        $wallet->deposit((int) $invoice->due_amount - (int) $total_commission, [
            'order_id' => $payment->payment_number,
            'currency' => $invoice->currency_code,
            'invoice' => $invoice->invoice_number,
            'fee' => money($total_commission, $invoice->currency_code)->format(),
            'description' => 'invoice_payment_made_by_customer',
        ]);

        // Update Invoice Status
        $invoice->status = Invoice::STATUS_COMPLETED;
        $invoice->paid_status = Invoice::STATUS_PAID;
        $invoice->due_amount = 0;
        $invoice->save();

        // Send Mail to Customer
        try {
            Mail::to($invoice->customer->email)->send(new PaymentReceiptToCustomer($payment));
        } catch (\Exception $th) {
            session()->flash('alert-danger', __('messages.email_could_not_sent'));
        }
    
        // Log activity
        activity()->on($payment->customer)->by($payment)
            ->log(__('messages.activity_payment_receipt_emailed', ['payment_number' => $payment->payment_number]));

        // Dispatch InvoicePaidEvent
        InvoicePaidEvent::dispatch($invoice);

        return $payment;
    }
}