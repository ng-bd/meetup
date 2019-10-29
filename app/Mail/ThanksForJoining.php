<?php

namespace App\Mail;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ThanksForJoining extends Mailable
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
        Log::info("Sending ticket with thanks to email for the attendee: " . $attendee->id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('emails.payment.ticket', compact('attendee'));
        return $this->subject("Thanks for joining ".env("EVENT_TITLE"))
                ->attachData($pdf->output(), 'Ticket.pdf')
                ->view('emails.thanks', compact('attendee'));
    }
}
