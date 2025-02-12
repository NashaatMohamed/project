<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Invoice;

class AutoArchiveInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:archive:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto archive invoices';

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
        // find paid invoices where paid date < 30 days
        $date = Carbon::now()->subDays(30)->format('Y-m-d');
        $invoices = Invoice::paid()->whereDate('due_date', '<', $date)->get();

        foreach ($invoices as $invoice) {
            $check_auto_archive_enabled = get_company_setting('invoice_auto_archive', $invoice->company_id);
            if ($check_auto_archive_enabled) {
                $invoice->is_archived = true;
                $invoice->save();

                printf("Invoice %s is ARCHIVED \n", $invoice->invoice_number);
            }
        }
    }
}
