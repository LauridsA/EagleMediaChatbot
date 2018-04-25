<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class ProjectConversation extends Conversation
{
    public function testerson(){
        $this->say('made it through project ctr to project convo');
    }
    public function run()
    {
        $this->testerson();
    }
}