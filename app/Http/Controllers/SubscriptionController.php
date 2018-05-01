<?php

namespace App\Http\Controllers;

use BotMan\Drivers\Facebook\FacebookDriver;
use Illuminate\Http\Request;
use App\Conversations\SubscriptionConversation;
use BotMan\BotMan\BotMan;

class SubscriptionController extends Controller
{
    //recieve bot instance and start sub convo
    public function startSubscriptionConversation(Botman $bot, string $recipientID)
    {

        $bot->startConversation(new SubscriptionConversation(), $recipientID, FacebookDriver::class);
        //grab info from conversation and send to DB later
    }
}
