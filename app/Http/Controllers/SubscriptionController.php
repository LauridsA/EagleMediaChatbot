<?php

namespace App\Http\Controllers;

use App\Conversations\SubscriptionConversation;
use BotMan\BotMan\BotMan;
use App\FacebookSubscription; //TODO why is this broken????????????

class SubscriptionController extends Controller
{
    public function startSubscriptionConversation(Botman $bot)
    {
        $bot->startConversation(new SubscriptionConversation());
    }

    public function addUserToLabel($id){
        $whatever = new FacebookSubscription(); //TODO what is wrong here?
    }
}
