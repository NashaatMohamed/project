<?php

namespace App\Mails;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreditNoteToCustomer extends Mailable
{
    use SerializesModels;

    /**
     * Public Variables
     */
    public $credit_note;
    public $company;
    public $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($credit_note)
    {
        $this->credit_note = $credit_note;
        $this->company = $credit_note->company;
        $this->customer = $credit_note->customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->replaceTags($this->company->getSetting('credit_note_mail_subject'));
        $mail_content = $this->replaceTags($this->company->getSetting('credit_note_mail_content'));

        return $this->subject($subject)
            ->view('emails.mails.credit_note_to_customer')
            ->with([
                'subject' => $subject, 
                'mail_content' => $mail_content
            ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function replaceTags($text) {
        $credit_note_url = route('customer_portal.credit_notes.details', ['customer' => $this->customer->uid, 'credit_note' => $this->credit_note->uid]);
        $tag_list = [
            '{company.name}' => $this->company->name,
            '{customer.display_name}' => $this->customer->display_name,
            '{customer.contact_name}' => $this->customer->display_name,
            '{customer.email}' => $this->customer->email,
            '{customer.phone}' => $this->customer->phone, 
            '{credit_note.number}' => $this->credit_note->credit_note_number,
            '{credit_note.link}' => '<a href="'. $credit_note_url .'">'. $credit_note_url .'</a>',
            '{credit_note.date}' => $this->credit_note->formatted_credit_note_date,
            '{credit_note.reference}' => $this->credit_note->reference_number,
            '{credit_note.notes}' => $this->credit_note->notes, 
            '{credit_note.sub_total}' => money($this->credit_note->sub_total, $this->credit_note->currency_code)->format(),
            '{credit_note.total}' => money($this->credit_note->total, $this->credit_note->currency_code)->format(),
        ];
        foreach ($tag_list as $tag => $value) {
            $text = str_replace($tag, $value, $text);
        }
        return $text;
    }
}