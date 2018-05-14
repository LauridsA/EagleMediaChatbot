<?php

namespace App\Conversations;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\TopicController;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SubscriptionConversation extends Conversation
{

    public function subByEmail()
    {

        $this->ask('asd', function(Answer $answer)
        {
            if (filter_var($answer->getText(), FILTER_VALIDATE_EMAIL)) {
                try {
                    $ctr = new ClientController();
                    $newClient = $ctr->saveNewClient($answer->getText());
                    $this->say('Din email er blevet registreret som: ' . $newClient['email']);
                    $this->exitConversation();
                } catch (\Exception $exception) {
                    $this->say('Der skete en fejl. Prøv igen senere.');
                    return $this->exitConversation();
                }

            }
        });
    }
}