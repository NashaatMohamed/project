<?php

namespace App\Mails;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiringTrial extends Mailable
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
        $subject = __('messages.expiring_trial_mail_subject', ['app_name' => get_system_setting('application_name')]);
        $mail_content = __('messages.expiring_trial_mail_content', ['app_name' => get_system_setting('application_name')]);

        return $this->subject($subject)
            ->view('emails.mails.expiring_reminder_to_user')
            ->with([
                'subject' => $subject, 
                'mail_content' => $mail_content
            ]);
    }
}