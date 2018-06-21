<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterestTrigger extends Model
{
    protected $fillable = [
        'message_id', 'interest_id',
    ];
}
