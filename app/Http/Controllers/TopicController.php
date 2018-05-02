<?php

namespace App\Http\Controllers;

use App\Conversations\TopicConversation;
use BotMan\BotMan\BotMan;

class TopicController extends Controller
{
    public function startTopicConversation(Botman $bot)
    {
        $bot->startConversation(new TopicConversation());
    }
}
