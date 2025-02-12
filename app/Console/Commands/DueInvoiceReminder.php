<?php

namespace App\Console\Commands;

use App\Mails\DueInvoiceToCustomer;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;

class DueInvoiceReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:due:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send due reminder emails';

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
        // Get due invoices
        $now = Carbon::now()->format('Y-m-d');
        $invoices = Invoice::unpaid()->whereDate('due_date', '>', $now)->get();

        // Loop invoices
        foreach ($invoices as $invoice) {
            $currentCompany = $invoice->company;
            $timezone = get_company_setting('timezone', $currentCompany->id);
            $invoice_due_reminder_1_before_days = get_company_setting('invoice_due_reminder_1_before_days', $currentCompany->id);
            $invoice_due_reminder_2_before_days = get_company_setting('invoice_due_reminder_2_before_days', $currentCompany->id);
            $now = Carbon::now()->timezone($timezone)->format('Y-m-d');
            $due_date = Carbon::parse($invoice->due_date)->timezone($timezone);

            // Reminder 1
            if ($invoice_due_reminder_1_before_days) {
                // Check the reminder day is today
                if ($due_date->subDays($invoice_due_reminder_1_before_days)->format('Y-m-d') == $now) {
                    printf("Invoice %s first due reminder sending to customer \n", $invoice->invoice_number);

                    // Send mail to customer
                    try {
                        Mail::to($invoice->customer->email)->send(new DueInvoiceToCustomer($invoice));
                    } catch (\Exception $th) {
                        //
                    }

                    // Log the activity 
                    activity()->on($invoice->customer)->by($invoice)
                        ->log(__('messages.activity_first_due_reminder', ['invoice_number' => $invoice->invoice_number]));
                }
            }

            // Reminder 2
            if ($invoice_due_reminder_2_before_days) {
                // Check the reminder day is today
                if ($due_date->subDays($invoice_due_reminder_2_before_days)->format('Y-m-d') == $now) {
                    printf("Invoice %s second due reminder sending to customer \n", $invoice->invoice_number);

                    // Send mail to customer
                    try {
                        Mail::to($invoice->customer->email)->send(new DueInvoiceToCustomer($invoice));
                    } catch (\Exception $th) {
                        //
                    }

                    // Log the activity
                    activity()->on($invoice->customer)->by($invoice)
                        ->log(__('messages.activity_second_due_reminder', ['invoice_number' => $invoice->invoice_number]));
                }
            }
        }

    }
}
