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
    //
    public function startSubscriptionConversation()
    {
        $question = Question::create('Spændende! For at kunne gøre dette, skal jeg bruge din email')
            ->fallback('unable to ask question')
            ->callbackId('start_sub_convo')
            ->addButtons([
                Button::create('Brug Email')->value('email'),
                Button::create('Glemt det')->value('exit')
            ]);
        $this->ask($question, function (Answer $answer)
        {
            switch ($answer->getValue())
            {
                case 'email':
                    $this->subByEmail();
                    break;
                case 'exit':
                    $this->exitConversation();
                    break;
                default:
                    $this->say('hvad? Brug helst knapperne ....');
                    $this->startSubscriptionConversation();
                break;
            }
        });
    }

    public function subByEmail()
    {
        $question = Question::create('Fedt! Skriv din email til mig (eksempel: KasperStuck@gmail.com). Hvis du vil ud så tryk på "Glem det" eller skriv "tilbage".')
            ->fallback('unable to ask question')
            ->callbackId('sub_by_email')
            ->addButtons([
                Button::create('Glemt det')->value('exit')
            ]);
        $this->ask($question, function(Answer $answer)
        {
            if($answer->isInteractiveMessageReply() || $answer->getText() == 'tilbage'){
                return $this->exitConversation();
            }

            if (filter_var($answer->getText(), FILTER_VALIDATE_EMAIL)) {
                $ctr = new ClientController();
                $newClient = $ctr->saveNewClient($answer->getText());
                $this->say('Din email er blevet registreret som: ' . $newClient['email']);
                $this->bot->startConversation(new TopicConversation());
            } else {
                $this->say('Det ser ud til, at der er en fejl i din email. Prøv igen.');
                $this->subByEmail();
            }
        });
    }

    public function exitConversation()
    {
        $this->say('Okay! Du kan altid skifte mening ved brug af burger-menuen nederst til højre');
        $topicCtr = new TopicController();
        $topicCtr->startTopicConversation($this->getBot());
    }

    public function run()
    {
        $this->startSubscriptionConversation();
    }
}