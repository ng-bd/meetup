<?php

namespace App\Models;

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
