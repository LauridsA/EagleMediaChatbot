<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class ClientController extends Controller
{
    //
    public function saveNewClient(String $email)
    {
        // save new client to db and return it to caller. Needs all fillable fields set it seems.
        return $client = App\Client::create(['email'=>$email, 'subscription_key'=>'ok', 'name'=>'John Doe']);
    }
}
