<?php

namespace App;

use Creativeorange\Gravatar\Gravatar;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'profession',
        'social_profile_url',
        'is_paid',
        'is_show_in_list',
        'misc'
    ];

    protected $casts = [
        'misc' => 'array'
    ];
}
