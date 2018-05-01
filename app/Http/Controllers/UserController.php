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
    public function addUserSubscription(String $userSub, String $email)
    {
        $user = new User();
        $user->name = 'John';
        $user->email = email;
        $user->password = 'someplaintext';
        $user->subscription_key = $userSub;

        $user->save();
    }
}
