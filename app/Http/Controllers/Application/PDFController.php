<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use App\Models\Invoice;
use App\Models\Estimate;
use App\Models\Payment;
use PDF;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    /**
     * Get Invoice Pdf
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return pdf
     */
    public function invoice(Request $request)
    {
        $invoice = Invoice::findByUid($request->invoice);

        $pdf = PDF::loadView('pdf.invoice.'.$invoice->template_view, ['invoice' => $invoice]);
 
        //Render or Download
        if($request->has('download')) {
            return $pdf->download($invoice->invoice_number . '-invoice.pdf');
        } else {
            return $pdf->stream('invoice.pdf');
        }
    }

    /**
     * Get Credit Note Pdf
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return pdf
     */
    public function credit_note(Request $request)
    {
        $credit_note = CreditNote::findByUid($request->credit_note);

        $pdf = PDF::loadView('pdf.credit_note.'.$credit_note->template_view, ['credit_note' => $credit_note]);
 
        //Render or Download
        if($request->has('download')) {
            return $pdf->download($credit_note->credit_note_number . '-credit-note.pdf');
        } else {
            return $pdf->stream('credit_note.pdf');
        }
    }

    /**
     * Get Estimate Pdf
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return pdf
     */
    public function estimate(Request $request)
    {
        $estimate = Estimate::findByUid($request->estimate);

        $pdf = PDF::loadView('pdf.estimate.'.$estimate->template_view, ['estimate' => $estimate]);

        //Render or Download
        if($request->has('download')) {
            return $pdf->download($estimate->estimate_number . '-estimate.pdf');
        } else {
            return $pdf->stream('estimate.pdf');
        }
    }

    /**
     * Get Payment Pdf
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return pdf
     */
    public function payment(Request $request)
    {
        $payment = Payment::findByUid($request->payment);

        $template = $payment->company->getSetting('payment_template');
        $pdf = PDF::loadView('pdf.payment.'.$template, ['payment' => $payment]);

        //Render or Download
        if($request->has('download')) {
            return $pdf->download($payment->payment_number . '-payment.pdf');
        } else {
            return $pdf->stream('payment.pdf');
        }
    }
}
