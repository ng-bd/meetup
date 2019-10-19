<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'profession',
        'social_profile_url',
        'is_paid',
        'misc'
    ];

    protected $casts = [
        'misc' => 'array'
    ];

    public function getTshirtAttribute()
    {
        return $this->misc[ "tshirt" ];
    }

}
