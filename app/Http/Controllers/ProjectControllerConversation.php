<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use App\Conversations\ProjectConversation;
class ProjectControllerConversation extends Controller
{
    public function startConversation()
    {
        new ProjectConversation();
    }
}