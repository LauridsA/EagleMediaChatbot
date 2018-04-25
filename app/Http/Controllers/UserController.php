<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    public function addUserSubscription(String $userSub)
    {
        $user = new User();
        $user->name = 'John';
        $user->email = 'john@doe.com';
        $user->password = 'someplaintext';
        $user->subscription_key = $userSub;

        $user->save();
    }
}
