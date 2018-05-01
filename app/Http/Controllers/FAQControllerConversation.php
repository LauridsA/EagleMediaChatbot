<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use App\Conversations\FAQConversation;
class FAQControllerConversation extends Controller
{
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new FAQConversation());
    }
}