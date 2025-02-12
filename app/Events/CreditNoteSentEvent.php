<?php

namespace App\Events;

use App\Models\CreditNote;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreditNoteSentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The credit_note instance.
     *
     * @var \App\Models\CreditNote
     */
    public $credit_note;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CreditNote $credit_note)
    {
        $this->credit_note = $credit_note;
    }
}
