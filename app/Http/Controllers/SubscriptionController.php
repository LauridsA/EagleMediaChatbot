<?php

namespace App\Http\Controllers;

use BotMan\Drivers\Facebook\FacebookDriver;
use Illuminate\Http\Request;
use App\Conversations\SubscriptionConversation;
use BotMan\BotMan\BotMan;

class SubscriptionController extends Controller
{
    //recieve bot instance and start sub convo
    public function startSubscriptionConversation(Botman $bot)
    {

        $bot->startConversation(new SubscriptionConversation());
        //grab info from conversation and send to DB later
    }
}
