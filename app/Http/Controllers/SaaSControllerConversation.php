<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use App\Conversations\SaaSConversation;
class SaaSControllerConversation extends Controller
{
    public function startConversation(BotMan $bot) //maybe nothing here???
    {
        $bot->startConversation(new SaaSConversation()); //creates new bot????
    }
}