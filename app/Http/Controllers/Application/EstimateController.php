<?php

namespace App\Http\Controllers\Application;

use App\Events\EstimateSentEvent;
use App\Http\Controllers\Controller;
use App\Mails\EstimateToCustomer;
use App\Models\Estimate;
use Illuminate\Http\Request;
use App\Http\Requests\Application\Estimate\Store;
use App\Http\Requests\Application\Estimate\Update;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Carbon\Carbon;

class EstimateController extends Controller
{
    /**
     * Display Estimates Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Query Estimates by Company and Tab
        if($request->tab == 'all') {
            $query = Estimate::findByCompany($currentCompany->id)->orderBy('estimate_number');
            $tab = 'all';
        } else if($request->tab == 'sent') {
            $query = Estimate::findByCompany($currentCompany->id)->nonArchived()->active()->orderBy('expiry_date');
            $tab = 'sent';
        } else {
            $query = Estimate::findByCompany($currentCompany->id)->nonArchived()->drafts()->orderBy('estimate_number');
            $tab = 'drafts';
        }

        // Apply Filters and Paginate
        $estimates = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::partial('estimate_number'),
                AllowedFilter::scope('from'),
                AllowedFilter::scope('to'),
            ])
            ->paginate()
            ->appends(request()->query());

        return view('application.estimates.index', [
            'estimates' => $estimates,
            'tab' => $tab,
        ]);
    }

    /**
     * Display the Form for Creating New Estimate
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Get next Estimate number if the auto generation option is enabled
        $estimate_prefix = $currentCompany->getSetting('estimate_prefix');
        $next_estimate_number = Estimate::getNextestimateNumber($currentCompany->id, $estimate_prefix);

        // Create new Estimate model and set estimate_number and company_id
        // so that we can use them in the form
        $estimate = new Estimate();
        $estimate->estimate_number = $next_estimate_number ?? 0;
        $estimate->company_id = $currentCompany->id;

        // Also for filling form data and the ui
        $customers = $currentCompany->customers;
        $products = $currentCompany->products;
        $tax_per_item = (boolean) $currentCompany->getSetting('tax_per_item');
        $discount_per_item = (boolean) $currentCompany->getSetting('discount_per_item');

        return view('application.estimates.create', [
            'estimate' => $estimate,
            'customers' => $customers,
            'products' => $products,
            'tax_per_item' => $tax_per_item,
            'discount_per_item' => $discount_per_item,
        ]);
    }

    /**
     * Store the Estimate in Database
     *
     * @param \App\Http\Requests\Application\Estimate\Store $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Store $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Redirect back
        $canAdd = $currentCompany->subscription('main')->canUseFeature('estimates_per_month');
        if (!$canAdd) {
            session()->flash('alert-danger', __('messages.you_have_reached_the_limit'));
            return redirect()->route('estimates', ['company_uid' => $currentCompany->uid]);
        }

        // Get company based settings
        $tax_per_item = (boolean) $currentCompany->getSetting('tax_per_item');
        $discount_per_item = (boolean) $currentCompany->getSetting('discount_per_item');

        // Save Estimate to Database
        $estimate = Estimate::create([
            'estimate_date' => $request->estimate_date,
            'expiry_date' => $request->expiry_date,
            'estimate_number' => $request->estimate_number,
            'reference_number' => $request->reference_number,
            'customer_id' => $request->customer_id,
            'company_id' => $currentCompany->id,
            'status' => Estimate::STATUS_DRAFT,
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

        // Arrays of data for storing Estimate Items
        $products = $request->product;
        $quantities = $request->quantity;
        $taxes = $request->taxes;
        $prices = $request->price;
        $totals = $request->total;
        $discounts = $request->discount;

        // Add products (estimate items)
        for ($i=0; $i < count($products); $i++) {
            $product = Product::firstOrCreate(
                ['id' => $products[$i], 'company_id' => $currentCompany->id],
                ['name' => $products[$i], 'price' => $prices[$i], 'hide' => 1]
            );

            $item = $estimate->items()->create([
                'product_id' => $product->id,
                'company_id' => $currentCompany->id,
                'quantity' => $quantities[$i],
                'discount_type' => 'percent',
                'discount_val' => $discounts[$i] ?? 0,
                'price' => $prices[$i],
                'total' => $totals[$i],
            ]);

            // Add taxes for Estimate Item if it is given
            if ($taxes && array_key_exists($i, $taxes)) {
                foreach ($taxes[$i] as $tax) {
                    $item->taxes()->create([
                        'tax_type_id' => $tax
                    ]);
                }
            }
        }

        // If Estimate based taxes are given
        if ($request->has('total_taxes')) {
            foreach ($request->total_taxes as $tax) {
                $estimate->taxes()->create([
                    'tax_type_id' => $tax
                ]);
            }
        }

        // Update custom field values
        $estimate->addCustomFields($request->custom_fields);

        // Record product 
        $currentCompany->subscription('main')->recordFeatureUsage('estimates_per_month');

        session()->flash('alert-success', __('messages.estimate_added'));
        return redirect()->route('estimates.details', ['estimate' => $estimate->id, 'company_uid' => $currentCompany->uid]);
    }

    /**
     * Display the Estimate Details Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $estimate = Estimate::findOrFail($request->estimate);

        return view('application.estimates.details', [
            'estimate' => $estimate,
        ]);
    }

    /**
     * Send an email to customer about the Estimate
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $estimate = Estimate::findOrFail($request->estimate);

        // If demo mode is active then block this action
        if (config('app.is_demo')) {
            session()->flash('alert-danger', __('messages.action_blocked_in_demo'));
            return redirect()->route('estimates.details', ['estimate' => $estimate->id, 'company_uid' => $currentCompany->uid]);
        };

        // Send mail to customer
        try {
            Mail::to($estimate->customer->email)->send(new EstimateToCustomer($estimate));
        } catch (\Exception $e) {
            session()->flash('alert-danger', __('messages.email_could_not_sent'));
        }

        // Log the activity
        activity()->on($estimate->customer)->by($estimate)
            ->log(__('messages.activity_estimate_emailed', ['estimate_number' => $estimate->estimate_number]));

        // Change the status of the Estimate
        if ($estimate->status == Estimate::STATUS_DRAFT) {
            $estimate->status = Estimate::STATUS_SENT;
            $estimate->save();
        }

        // Dispatch EstimateSentEvent
        EstimateSentEvent::dispatch($estimate);

        session()->flash('alert-success', __('messages.an_email_sent_to_customer'));
        return redirect()->route('estimates.details', ['estimate' => $estimate->id, 'company_uid' => $currentCompany->uid]);
    }

    /**
     * Change Status of the Estimate by Given Status
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function mark(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $estimate = Estimate::findOrFail($request->estimate);

        // Mark the Estimate by given status
        if ($request->status && $request->status == 'sent') {
            $estimate->status = Estimate::STATUS_SENT;
        } else if ($request->status && $request->status == 'accepted') {
            $estimate->status = Estimate::STATUS_ACCEPTED;
        } else if ($request->status && $request->status == 'rejected') {
            $estimate->status = Estimate::STATUS_REJECTED;
        }

        // Save the status
        $estimate->save();

        session()->flash('alert-success', __('messages.estimate_status_updated'));
        return redirect()->route('estimates.details', ['estimate' => $estimate->id, 'company_uid' => $currentCompany->uid]);
    }

    /**
     * Display the Form for Editing Estimate
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        $estimate = Estimate::findOrFail($request->estimate);

        // Filling form data and the ui
        $customers = $currentCompany->customers;
        $products = $currentCompany->products;

        return view('application.estimates.edit', [
            'estimate' => $estimate,
            'customers' => $customers,
            'products' => $products,
            'tax_per_item' => $estimate->tax_per_item,
            'discount_per_item' => $estimate->discount_per_item,
        ]);
    }

    /**
     * Update the Estimate in Database
     *
     * @param \App\Http\Requests\Application\Estimate\Update $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Update $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Find Estimate or Fail (404 Http Error)
        $estimate = Estimate::findOrFail($request->estimate);

        // Update Estimate
        $estimate->update([
            'estimate_date' => $request->estimate_date,
            'expiry_date' => $request->expiry_date,
            'estimate_number' => $request->estimate_number,
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

        // Remove old estimate items
        $estimate->items()->delete();

        // Add products (estimate items)
        for ($i=0; $i < count($products); $i++) {
            $product = Product::firstOrCreate(
                ['id' => $products[$i], 'company_id' => $currentCompany->id],
                ['name' => $products[$i], 'price' => $prices[$i], 'hide' => 1]
            );

            $item = $estimate->items()->create([
                'product_id' => $product->id,
                'company_id' => $currentCompany->id,
                'quantity' => $quantities[$i],
                'discount_type' => 'percent',
                'discount_val' => $discounts[$i] ?? 0,
                'price' => $prices[$i],
                'total' => $totals[$i],
            ]);

            // Add taxes for Estimate Item if it is given
            if ($taxes && array_key_exists($i, $taxes)) {
                foreach ($taxes[$i] as $tax) {
                    $item->taxes()->create([
                        'tax_type_id' => $tax
                    ]);
                }
            }
        }

        // Remove old estimate taxes
        $estimate->taxes()->delete();

        // If Estimate based taxes are given
        if ($request->has('total_taxes')) {
            foreach ($request->total_taxes as $tax) {
                $estimate->taxes()->create([
                    'tax_type_id' => $tax
                ]);
            }
        }

        // Update custom field values
        $estimate->updateCustomFields($request->custom_fields);

        session()->flash('alert-success',  __('messages.estimate_updated'));
        return redirect()->route('estimates.details', ['estimate' => $estimate->id, 'company_uid' => $currentCompany->uid]);
    }

    /**
     * Convert the Estimate to an Invoice
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function convert(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();

        // Find Estimate or Fail (404 Http Error)
        $estimate = Estimate::findOrFail($request->estimate);

        // Redirect back
        $canAdd = $currentCompany->subscription('main')->canUseFeature('invoices_per_month');
        if (!$canAdd) {
            session()->flash('alert-danger', __('messages.you_have_reached_the_limit'));
            return redirect()->route('estimates.details', ['estimate' => $estimate->id, 'company_uid' => $currentCompany->uid]);
        }

        // Convert to Invoice
        $invoice = $estimate->convertToInvoice();

        // Record usage
        $currentCompany->subscription('main')->recordFeatureUsage('invoices_per_month');
 
        session()->flash('alert-success',  __('messages.invoice_added'));
        return redirect()->route('invoices.details', ['invoice' => $invoice->id, 'company_uid' => $currentCompany->uid]);
    }

    /**
     * Delete the Estimate
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $user = $request->user();
        $currentCompany = $user->currentCompany();
        
        $estimate = Estimate::findOrFail($request->estimate);

        // Delete Estimate from Database
        $estimate->delete();

        // Reduce feature
        $currentCompany->subscription('main')->reduceFeatureUsage('estimates_per_month');

        session()->flash('alert-success',  __('messages.estimate_deleted'));
        return redirect()->route('estimates', ['company_uid' => $currentCompany->uid]);
    }
}
