<?php

namespace App\Jobs;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendee;
    protected $mail;

    /**
     * Create a new job instance.
     *
     * @param Attendee $attendee
     * @param $mail
     */
    public function __construct(Attendee $attendee, Mailable $mail)
    {
        $this->attendee = $attendee;
        $this->mail = $mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->attendee->email)->send($this->mail);
    }
}
