<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    protected $fillable = [
        'user_id' ,'interest_id',
    ];
}
