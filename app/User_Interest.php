<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Interest extends Model
{
    protected $fillable = [
        'interest_id', 'user_id'
    ];
}
