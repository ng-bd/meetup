<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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
}
