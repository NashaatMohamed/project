<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Payment;

class AutoArchivePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:archive:payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto archive payments';

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
        // find paid payments where paid date < 30 days
        $date = Carbon::now()->subDays(30)->format('Y-m-d');
        $payments = Payment::whereDate('payment_date', '<', $date)->get();

        foreach ($payments as $payment) {
            $check_auto_archive_enabled = get_company_setting('payment_auto_archive', $payment->company_id);
            if ($check_auto_archive_enabled) {
                $payment->is_archived = true;
                $payment->save();

                printf("Payment %s is ARCHIVED \n", $payment->payment_number);
            }
        }
    }
}
