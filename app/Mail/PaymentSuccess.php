<?php

namespace App\Mail;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;

    protected $attendee;

    /**
     * Create a new message instance.
     *
     * @param Attendee $attendee
     */
    public function __construct( Attendee $attendee )
    {
        $this->attendee = $attendee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $attendee = $this->attendee;
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('emails.payment.ticket', compact('attendee'));
        return $this->subject("Successfully Payment for ".env("EVENT_TITLE"))
                ->attachData($pdf->output(), 'Ticket.pdf')
                ->view('emails.payment.create', compact('attendee'));
    }
}
