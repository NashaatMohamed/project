<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GeneralNotification extends Notification
{
    /**
     * Public Variables
     */
    public $subject;
    public $content;

    /**
     * Create a notification instance.
     *
     * @param string  $subject
     * @param string  $content
     * 
     * @return void
     */
    public function __construct($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.mails.general', ['mail_content' => $this->content]);
    }
}