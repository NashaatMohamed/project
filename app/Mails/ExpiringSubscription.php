<?php

namespace App\Mails;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiringSubscription extends Mailable
{
    use SerializesModels;

    /**
     * Public Variables
     */
    public $subscription;
    public $company;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
        $this->company = null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = get_system_setting('expiring_subscription_overdue_mail_subject');
        $mail_content = get_system_setting('expiring_subscription_overdue_mail_content');

        return $this->subject($subject)
            ->view('emails.mails.expiring_reminder_to_user')
            ->with([
                'subject' => $subject, 
                'mail_content' => $mail_content
            ]);
    }
}