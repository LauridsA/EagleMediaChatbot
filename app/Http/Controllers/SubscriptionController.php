<?php

namespace App\Http\Controllers;

use App\Conversations\SubscriptionConversation;
use BotMan\BotMan\BotMan;

class SubscriptionController extends Controller
{
    public function startSubscriptionConversation(Botman $bot)
    {
        $bot->startConversation(new SubscriptionConversation());
    }
}
