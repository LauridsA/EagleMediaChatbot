<?php

namespace App\Http\Controllers;

use App\Conversations\BroadcastConversation;
use App\Conversations\SubscriptionConversation;
use BotMan\BotMan\BotMan;
use App\FacebookSubscription;

class SubscriptionController extends Controller
{
    public function startSubscriptionConversation(Botman $bot)
    {
        $bot->startConversation(new SubscriptionConversation());
    }

    public function addUserToLabel($id)
    {
        $whatever = new FacebookSubscription();
        $whatever->addToLabel($id);
    }

    public function removeUserFromLabel($id)
    {
        $whatever = new FacebookSubscription();
        $whatever->removeFromLabel($id);
    }

    public function retrieveLabel($id)
    {
        $whatever = new FacebookSubscription();
        $whatever->retrieveLabel($id);
    }

    public function checkBroadcastStatus(Botman $bot){
        $bot->startConversation(new BroadcastConversation());
    }
}
