<?php

namespace App\Http\Controllers\Application;

use App\Events\CreditNoteSentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\CreditNote\RefundStore;
use Illuminate\Http\Request;
use App\Http\Requests\Application\CreditNote\Store;
use App\Http\Requests\Application\CreditNote\Update;
use App\Mails\CreditNoteToCustomer;
use App\Models\CreditNote;
use App\Models\CreditNoteRefund;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CreditNoteController extends Controller
{
    /**
     * Display CreditNotes Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Apply Filters and Paginate
        $credit_notes = QueryBuilder::for(CreditNote::class)
            ->allowedFilters([
                AllowedFilter::partial('credit_note_number'),
                AllowedFilter::scope('from'),
                AllowedFilter::scope('to'),
            ])
            ->paginate()
            ->appends(request()->query());

        return view('application.credit_notes.index', [
            'credit_notes' => $credit_notes,
        ]);
    }

    /**
     * Display the Form for Creating New CreditNote
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get next Credit Note number if the auto generation option is enabled
        $credit_note_prefix = $currentCompany->getSetting('credit_note_prefix');
        $next_credit_note_number = CreditNote::getNextCreditNoteNumber($currentCompany->id, $credit_note_prefix);

        // Create new CreditNote model and set credit_note_number and company_id
        // so that we can use them in the form
        $credit_note = new CreditNote();
        $credit_note->credit_note_number = $next_credit_note_number ?? 0;
        $credit_note->company_id = $currentCompany->id;

        // Also for filling form data and the ui
        $customers = $currentCompany->customers;
        $products = $currentCompany->products;
        $tax_per_item = (boolean) $currentCompany->getSetting('tax_per_item');
        $discount_per_item = (boolean) $currentCompany->getSetting('discount_per_item');

        return view('application.credit_notes.create', [
            'credit_note' => $credit_note,
            'customers' => $customers,
            'products' => $products,
            'tax_per_item' => $tax_per_item,
            'discount_per_item' => $discount_per_item,
        ]);
    }

    /**
     * Store the CreditNote in Database
     *
     * @param \App\Http\Requests\Application\CreditNote\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get company based settings
        $tax_per_item = (boolean) $currentCompany->getSetting('tax_per_item');
        $discount_per_item = (boolean) $currentCompany->getSetting('discount_per_item');

        // Save CreditNote to Database
        $credit_note = CreditNote::create([
            'credit_note_date' => $request->credit_note_date,
            'credit_note_number' => $request->credit_note_number,
            'reference_number' => $request->reference_number,
            'customer_id' => $request->customer_id,
            'company_id' => $currentCompany->id,
            'status' => CreditNote::STATUS_DRAFT,
            'discount_type' => 'percent',
            'discount_val' => $request->total_discount ?? 0,
            'sub_total' => $request->sub_total,
            'total' => $request->grand_total,
            'notes' => $request->notes,
            'private_notes' => $request->private_notes,
            'tax_per_item' => $tax_per_item,
            'discount_per_item' => $discount_per_item,
            'template_id' => $request->template_id,
        ]);

        // Arrays of data for storing CreditNote Items
        $products = $request->product;
        $quantities = $request->quantity;
        $taxes = $request->taxes;
        $prices = $request->price;
        $totals = $request->total;
        $discounts = $request->discount;

        // Add products (credit_note items)
        for ($i=0; $i < count($products); $i++) {
            $product = Product::firstOrCreate(
                ['id' => $products[$i], 'company_id' => $currentCompany->id],
                ['name' => $products[$i], 'price' => $prices[$i], 'hide' => 1]
            );

            $item = $credit_note->items()->create([
                'product_id' => $product->id,
                'company_id' => $currentCompany->id,
                'quantity' => $quantities[$i],
                'discount_type' => 'percent',
                'discount_val' => $discounts[$i] ?? 0,
                'price' => $prices[$i],
                'total' => $totals[$i],
            ]);

            // Add taxes for CreditNote Item if it is given
            if ($taxes && array_key_exists($i, $taxes)) {
                foreach ($taxes[$i] as $tax) {
                    $item->taxes()->create([
                        'tax_type_id' => $tax
                    ]);
                }
            }
        }

        // If CreditNote based taxes are given
        if ($request->has('total_taxes')) {
            foreach ($request->total_taxes as $tax) {
                $credit_note->taxes()->create([
                    'tax_type_id' => $tax
                ]);
            }
        }

        // Update custom field values
        $credit_note->addCustomFields($request->custom_fields);

        session()->flash('alert-success', __('messages.credit_note_added'));
        return redirect()->route('credit_notes.details', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]);
    }

    /**
     * Display the CreditNote Details Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $credit_note = CreditNote::findOrFail($request->credit_note);

        $payments = $credit_note->applied_payments()->orderBy('payment_number')->paginate(50);
        $refunds = $credit_note->refunds()->paginate(50);

        return view('application.credit_notes.details', [
            'credit_note' => $credit_note,
            'payments' => $payments,
            'refunds' => $refunds,
        ]);
    }

    /**
     * Send an email to customer about the CreditNote
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $credit_note = CreditNote::findOrFail($request->credit_note);

        // If demo mode is active then block this action
        if (config('app.is_demo')) {
            session()->flash('alert-danger', __('messages.action_blocked_in_demo'));
            return redirect()->route('credit_notes.details', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]);
        };

        // Send mail to customer
        try {
            Mail::to($credit_note->customer->email)->send(new CreditNoteToCustomer($credit_note));
        } catch (\Exception $e) {
            session()->flash('alert-danger', __('messages.email_could_not_sent'));
        }

        // Log the activity
        activity()->on($credit_note->customer)->by($credit_note)
            ->log(__('messages.activity_credit_note_emailed', ['credit_note_number' => $credit_note->credit_note_number]));

        // Change the status of the CreditNote
        if ($credit_note->status == CreditNote::STATUS_DRAFT) {
            $credit_note->status = CreditNote::STATUS_SENT;
            $credit_note->save();
        }

        // Dispatch CreditNoteSentEvent
        CreditNoteSentEvent::dispatch($credit_note);

        session()->flash('alert-success', __('messages.an_email_sent_to_customer'));
        return redirect()->route('credit_notes.details', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]);
    }

    /**
     * Change Status of the CreditNote by Given Status
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function mark(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $credit_note = CreditNote::findOrFail($request->credit_note);

        // Mark the CreditNote by given status
        if ($request->status && $request->status == 'sent') {
            $credit_note->status = CreditNote::STATUS_SENT;
        }

        // Save the status
        $credit_note->save();

        session()->flash('alert-success', __('messages.credit_note_status_updated'));
        return redirect()->route('credit_notes.details', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]);
    }

    /**
     * Display the Form for Editing CreditNote
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $credit_note = CreditNote::findOrFail($request->credit_note);

        // Filling form data and the ui
        $customers = $currentCompany->customers;
        $products = $currentCompany->products;

        return view('application.credit_notes.edit', [
            'credit_note' => $credit_note,
            'customers' => $customers,
            'products' => $products,
            'tax_per_item' => $credit_note->tax_per_item,
            'discount_per_item' => $credit_note->discount_per_item,
        ]);
    }

    /**
     * Update the CreditNote in Database
     *
     * @param \App\Http\Requests\Application\CreditNote\Update $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Find CreditNote or Fail (404 Http Error)
        $credit_note = CreditNote::findOrFail($request->credit_note);

        // Update CreditNote
        $credit_note->update([
            'credit_note_date' => $request->credit_note_date,
            'credit_note_number' => $request->credit_note_number,
            'reference_number' => $request->reference_number,
            'customer_id' => $request->customer_id,
            'discount_type' => 'percent',
            'discount_val' => $request->total_discount ?? 0,
            'sub_total' => $request->sub_total,
            'total' => $request->grand_total,
            'notes' => $request->notes,
            'private_notes' => $request->private_notes,
            'template_id' => $request->template_id,
        ]);

        // Posted Values
        $products = $request->product;
        $quantities = $request->quantity;
        $taxes = $request->taxes;
        $prices = $request->price;
        $totals = $request->total;
        $discounts = $request->discount;

        // Remove old credit_note items
        $credit_note->items()->delete();

        // Add products (credit_note items)
        for ($i=0; $i < count($products); $i++) {
            $product = Product::firstOrCreate(
                ['id' => $products[$i], 'company_id' => $currentCompany->id],
                ['name' => $products[$i], 'price' => $prices[$i], 'hide' => 1]
            );

            $item = $credit_note->items()->create([
                'product_id' => $product->id,
                'company_id' => $currentCompany->id,
                'quantity' => $quantities[$i],
                'discount_type' => 'percent',
                'discount_val' => $discounts[$i] ?? 0,
                'price' => $prices[$i],
                'total' => $totals[$i],
            ]);

            // Add taxes for CreditNote Item if it is given
            if ($taxes && array_key_exists($i, $taxes)) {
                foreach ($taxes[$i] as $tax) {
                    $item->taxes()->create([
                        'tax_type_id' => $tax
                    ]);
                }
            }
        }

        // Remove old credit_note taxes
        $credit_note->taxes()->delete();

        // If CreditNote based taxes are given
        if ($request->has('total_taxes')) {
            foreach ($request->total_taxes as $tax) {
                $credit_note->taxes()->create([
                    'tax_type_id' => $tax
                ]);
            }
        }

        // Update custom field values
        $credit_note->updateCustomFields($request->custom_fields);

        session()->flash('alert-success',  __('messages.credit_note_updated'));
        return redirect()->route('credit_notes.details', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]);
    }

    /**
     * Delete the CreditNote
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        
        $credit_note = CreditNote::findOrFail($request->credit_note);

        // Delete payments
        foreach ($credit_note->applied_payments as $payment) {
            // Delete
            $payment->deleteModel();
        }

        // Delete CreditNote from Database
        $credit_note->delete();

        session()->flash('alert-success',  __('messages.credit_note_deleted'));
        return redirect()->route('credit_notes', ['company_uid' => $currentCompany->uid]);
    }

    /**
     * Change Status of the CreditNote by Given Status
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function refund(Request $request)
    {
        $credit_note = CreditNote::findOrFail($request->credit_note);

        return view('application.credit_notes.refund', [
            'credit_note' => $credit_note,
        ]);
    }

    /**
     * Change Status of the CreditNote by Given Status
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function refund_store(RefundStore $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $credit_note = CreditNote::findOrFail($request->credit_note);

        // Check payment amount less than credit note amount
        if($request->amount > $credit_note->remaining_balance) {
            return redirect()->back()->withErrors(['amount' => __('messages.invalid_amount')]);
        }

        // Create a refund on database
        CreditNoteRefund::create([
            'credit_note_id' => $credit_note->id,
            'payment_method_id' => $request->payment_method_id,
            'refund_date' => $request->refund_date,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ]);

        session()->flash('alert-success', __('messages.refund_issued'));
        return redirect()->route('credit_notes.details', ['credit_note' => $credit_note->id, 'company_uid' => $currentCompany->uid]);
    }
}
