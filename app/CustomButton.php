<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomButton extends Model
{
    //
    protected $fillable = [
        'name', 'value', 'messages_id',  'next_message_id'
    ];
}
