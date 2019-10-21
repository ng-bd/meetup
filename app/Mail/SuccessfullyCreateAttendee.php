<?php

namespace App\Mail;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessfullyCreateAttendee extends Mailable
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
        return $this->subject("Registration for ".env("EVENT_TITLE"))->view('emails.attendee.create')->with([ 'attendee' => $this->attendee ]);
    }
}
