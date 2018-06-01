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
        try {
            $whatever = new FacebookSubscription();
            $whatever->addToLabel($id);

        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function removeUserFromLabel($id)
    {
        try {
            $whatever = new FacebookSubscription();
            $whatever->removeFromLabel($id);
        }catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }


    }

    public function retrieveLabel($id)
    {
        try {
            $whatever = new FacebookSubscription();
            $whatever->retrieveLabel($id);
        }catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }

    }

    public function checkBroadcastStatus(Botman $bot){
        $bot->startConversation(new BroadcastConversation());
    }
}
