<?php

namespace App\Console\Commands;

use App\Events\InvoiceSentEvent;
use App\Mails\InvoiceToCustomer;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;

class RecurringInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and create recurring invoices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get recurring invoices
        $invoices = Invoice::nonDraft()->recurring()->get();

        foreach ($invoices as $invoice) {
            // Company
            $currentCompany = $invoice->company;

            // Check can company add new invoice
            $canAdd = $currentCompany->subscription('main')->canUseFeature('invoices_per_month');
            if (!$canAdd) continue;

            // Check date
            $now = Carbon::now()->format('Y-m-d');
            $next_recurring_at = Carbon::parse($invoice->next_recurring_at)->format('Y-m-d');
            if ($now !== $next_recurring_at) continue;

            // Next invoice number
            $next_invoice_number = Invoice::getNextInvoiceNumber($currentCompany->id, $invoice->invoice_prefix);

            // New cycle
            $newcycle = intval($invoice->cycle);
            if (!in_array($newcycle, [-1, 0])) {
                $newcycle = $newcycle - 1;
            }

            // Save New Invoice to Database
            $new_invoice = Invoice::create([
                'invoice_date' => Carbon::now(),
                'due_date' => Carbon::parse($invoice->due_date)->addMonths($invoice->is_recurring)->format('Y-m-d'),
                'invoice_number' => $invoice->invoice_prefix . '-' . $next_invoice_number,
                'reference_number' => $invoice->reference_number,
                'customer_id' => $invoice->customer_id,
                'company_id' => $currentCompany->id,
                'status' => Invoice::STATUS_SENT,
                'paid_status' => Invoice::STATUS_UNPAID,
                'sub_total' => $invoice->sub_total,
                'discount_type' => 'percent',
                'discount_val' => $invoice->discount_val ?? 0,
                'total' => $invoice->total,
                'due_amount' => $invoice->due_amount,
                'notes' => $invoice->notes,
                'private_notes' => $invoice->private_notes,
                'tax_per_item' => $invoice->tax_per_item,
                'discount_per_item' => $invoice->discount_per_item,
                'is_recurring' => $invoice->is_recurring,
                'cycle' => $newcycle,
                'parent_invoice_id' => $invoice->parent_invoice_id || $invoice->id
            ]);

            // Set next recurring date
            if ($newcycle != 0) {
                $new_invoice->next_recurring_at = Carbon::parse($new_invoice->invoice_date)->addMonths(intval($invoice->is_recurring))->format('Y-m-d');
                $new_invoice->save();
            }

            // Remove recurring from old invoice
            $invoice->is_recurring = 0;
            $invoice->cycle = 0;
            $invoice->save();

            // Add products (invoice items)
            $old_invoice_items = $invoice->items;
            foreach ($old_invoice_items as $old_invoice_item) {
                $new_invoice_item = $new_invoice->items()->create([
                    'product_id' => $old_invoice_item->product_id,
                    'company_id' => $old_invoice_item->company_id,
                    'quantity' => $old_invoice_item->quantity,
                    'discount_type' => 'percent',
                    'discount_val' => $old_invoice_item->discount_val,
                    'price' => $old_invoice_item->price,
                    'total' => $old_invoice_item->total,
                ]);

                $old_invoice_item_taxes = $old_invoice_item->taxes;
                foreach ($old_invoice_item_taxes as $old_invoice_item_tax) {
                    $new_invoice_item->taxes()->create([
                        'tax_type_id' => $old_invoice_item_tax->tax_type_id,
                    ]);
                }
            }

            // If Invoice based taxes are given
            $invoice_taxes = $invoice->taxes;
            foreach ($invoice_taxes as $invoice_tax) {
                $new_invoice->taxes()->create([
                    'tax_type_id' => $invoice_tax->tax_type_id,
                ]);
            }

            // Add custom field values
            $custom_fields = $invoice->fields;
            foreach ($custom_fields as $field) {
                $new_invoice->fields()->create([
                    "custom_field_id" => $field->custom_field_id,
                    "company_id" => $field->company_id,
                    "type" => $field->company_id,
                    "string_answer" => $field->string_answer,
                    "number_answer" => intval($field->number_answer),
                    "boolean_answer" => boolval($field->boolean_answer),
                    "date_time_answer" => $field->date_time_answer,
                    "date_answer" => $field->date_answer,
                    "time_answer" => $field->time_answer,
                ]);
            }

            // Record usage 
            $currentCompany->subscription('main')->recordFeatureUsage('invoices_per_month');

            // Send mail to customer
            try {
                Mail::to($new_invoice->customer->email)->send(new InvoiceToCustomer($new_invoice));
            } catch (\Exception $th) {
                //
            }

            // Log the activity
            activity()->on($new_invoice->customer)->by($new_invoice)
                ->log(__('messages.activity_invoice_emailed', ['invoice_number' => $new_invoice->invoice_number]));

            // Dispatch InvoiceSentEvent
            InvoiceSentEvent::dispatch($new_invoice);
        }
    }
}
