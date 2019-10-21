<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Shipu\MuthoFun\Facades\MuthoFun;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attendee;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param $attendee
     * @param $message
     */
    public function __construct($attendee, $message)
    {
        $this->attendee = $attendee;
        $this->message = $message;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        MuthoFun::message($this->message, $this->attendee->mobile)->send();
    }
}
