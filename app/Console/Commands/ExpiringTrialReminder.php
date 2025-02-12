<?php

namespace App\Console\Commands;

use App\Mails\ExpiringTrial;
use App\Models\PlanSubscription;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ExpiringTrialReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiring_trials:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send expiring trial reminder emails';

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
        // Get expiring trials
        $subscriptions = PlanSubscription::findEndingTrial()->get();

        // Loop subscriptions
        foreach ($subscriptions as $subscription) {
            // Send mail to user
            try {
                Mail::to($subscription->company->owner->email)->send(new ExpiringTrial($subscription));
            } catch (\Exception $th) {
                //
            }
        }
    }
}