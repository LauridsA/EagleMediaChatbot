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
        $question = Question::create('Fedt! Skriv din email til mig (eksempel: KasperStuck@gmail.com). Hvis du vil ud så tryk på "Glem det" eller skriv "tilbage".')
            ->fallback('unable to ask question')
            ->callbackId('sub_by_email')
            ->addButtons([
                Button::create('Glem det')->value('exit')
            ]);
        $this->ask($question, function(Answer $answer)
        {
            if($answer->getText() == 'tilbage'){
                $this->say('Okay! Du kan altid skifte mening ved brug af burger-menuen nederst til venstre');
                return $this->exitConversation();
            }

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

            } else {
                $this->say('Det ser ud til, at der er en fejl i din email. Prøv igen.');
                $this->subByEmail();
            }
        });
    }

    /**
     * @return mixed
     */
    public function run()
    {
        // TODO: Implement run() method.
    }
}