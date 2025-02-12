<?php

namespace App\Console\Commands;

use App\Mails\ExpiringSubscription;
use App\Models\PlanSubscription;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ExpiringSubscriptionOverdueReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiring_subscription:reminder:overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send expiring subscription overdue reminder emails';

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
        $after_days_settings = get_system_setting('expiring_subscription_overdue_after_x_days');

        // Overdue Reminder
        if ($after_days_settings) { 
            // After days Carbon
            $from = Carbon::now()->subDays($after_days_settings)->startOfDay();
            $to = Carbon::now()->subDays($after_days_settings)->endOfDay();

            // Get expiring subscriptions
            $subscriptions = PlanSubscription::whereBetween('ends_at', [$from, $to])->get();

            // Loop subscriptions
            foreach ($subscriptions as $subscription) {
                // Send mail to user
                try {
                    Mail::to($subscription->company->owner->email)->send(new ExpiringSubscription($subscription));
                } catch (\Exception $th) {
                    //
                }
            }
        }
    }
}