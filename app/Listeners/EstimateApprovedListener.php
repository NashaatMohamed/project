<?php

namespace App\Listeners;

use App\Events\EstimateApprovedEvent;
use App\Notifications\EstimateApproved;
use Illuminate\Support\Facades\Notification;

class EstimateApprovedListener
{
    /**
     * Handle the event.
     *
     * @param  EstimateApprovedEvent  $event
     * @return void
     */
    public function handle(EstimateApprovedEvent $event)
    {
        $estimate = $event->estimate;
        $currentCompany = $event->estimate->company;

        // Convert to invoice
        $canAdd = $currentCompany->subscription('main')->canUseFeature('invoices_per_month');
        $autoConvert = (boolean) $currentCompany->getSetting('estimate_auto_convert');
        if ($autoConvert && $canAdd) {
            $estimate->convertToInvoice();
        }

        // Send Notification to Company User
        $notifyUsers = $currentCompany->users()->get()->filter(function ($user) {
            return $user->getSetting('notification_estimate_approved_or_rejected');
        });
        try {
            Notification::send($notifyUsers, new EstimateApproved($estimate));
        } catch (\Exception $th) {
            //throw $th;
        }
    }
}
