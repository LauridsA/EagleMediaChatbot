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
        try {
            $bot->startConversation(new SubscriptionConversation());
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
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
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function retrieveLabel($id)
    {
        try {
            $whatever = new FacebookSubscription();
            return $whatever->retrieveLabel($id);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }

    }

    public function checkBroadcastStatus(Botman $bot)
    {
        try {
            $bot->startConversation(new BroadcastConversation());
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function checkEmailStatus(Botman $bot)
    {
        try {
            $bot->startConversation(new SubscriptionConversation());
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }
}
