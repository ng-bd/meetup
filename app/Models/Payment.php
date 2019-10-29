<?php

namespace App\Models;

use App\Jobs\SendEmailJob;
use App\Jobs\SendSmsJob;
use App\Mail\PaymentSuccess;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Shipu\Watchable\Traits\WatchableTrait;

class Payment extends Model
{
    use WatchableTrait, CrudTrait;

    protected $fillable = [
        'attendee_id',
        'card_type',
        'amount',
        'transaction_id',
        'api_response',
    ];

    protected $casts = [
        'api_response' => 'array'
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class);
    }

    public function onModelCreated()
    {
        dispatch(new SendEmailJob($this->attendee, new PaymentSuccess($this->attendee)));
        if(env('SMS_ENABLED')) {
            dispatch(new SendSmsJob($this->attendee, env('PAYMENT_SUCCESS_MESSAGE')));
        }
    }
}
