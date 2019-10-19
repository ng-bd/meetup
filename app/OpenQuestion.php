<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpenQuestion extends Model
{
    protected $fillable = [
        'question',
        'name',
        'email',
        'mobile',
        'status'
    ];
}
