<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = [
      'message', 'image', 'delay', 'next_msg'
    ];
}
