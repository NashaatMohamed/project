<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Estimate;

class AutoArchiveEstimate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:archive:estimates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto archive estimates';

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
        // find expired, approved or rejected estimates where expiry date < 30 days
        $date = Carbon::now()->subDays(30)->format('Y-m-d');
        $estimates = Estimate::expiredOrApprovedOrRejected()->whereDate('expiry_date', '<', $date)->get();

        foreach ($estimates as $estimate) {
            $check_auto_archive_enabled = get_company_setting('estimate_auto_archive', $estimate->company_id);
            if ($check_auto_archive_enabled) {
                $estimate->is_archived = true;
                $estimate->save();

                printf("Estimate %s is ARCHIVED \n", $estimate->estimate_number);
            }
        }
    }
}
