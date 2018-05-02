<?php

namespace App\Conversations;

use App\Http\Controllers\ClientController;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Client;

class SubscriptionConversation extends Conversation
{
    //
    public function startSubConvo()
    {
        $question = Question::create('Spændende! For at kunne gøre dette, skal jeg bruge enten din email eller din XXX')
            ->fallback('unable to ask question')
            ->callbackId('start_sub_convo')
            ->addButtons([
                Button::create('Brug XXXX')->value('XXXX'),
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
                case 'XXXX':
                    $this->subByXXXX();
                    break;
                case 'exit':
                    $this->exitSub();
                    break;
//                default:
//                    $this->say('hvad? Brug helst knapperne ....');
//                    $this->startSubConvo();
//                break;
            }
        });
    }

    public function exitSub()
    {
        $this->say('Okay! Du kan altid skifte mening ved brug af burger-menuen nederst til højre');
    }

    public function subByEmail()
    {
        $this->ask('Fedt! Skriv din email til mig (eksempel: KasperStuck@gmail.com). Hvis du vil ud, skriv "tilbage"', function(Answer $answer) {
            // Save result
            $email = $answer->getText();
            if($email == 'tilbage')
            {
                $this->exitSub();
            }
            if (filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $ctr = new ClientController();
                $newClient = $ctr->saveNewClient($email);
                $this->say('Din email er blevet registreret som: ' . $newClient['email']);
            }
            else
            {
                $this->say('Det ser ud til, at der er en fejl i din email. prøv igen.');
                $this->subByEmail();
            }

        });
    }

    public function subByXXXX()
    {

    }

    public function subQuestion()
    {
        $question = Question::create('Super. Så skal jeg bare lige bruge din email.')
            ->fallback('Unable to ask question')
                ->callbackId('sub_question');
        $this->ask($question, function (Answer $answer) {
            switch (strtolower($answer->getText()))
            {
                case 'nej':
                    //blabla
                break;
            }
            if(strtolower($answer->getText()) == 'nej'){
                $this->getBot()->typesAndWaits(2);
                $this->say('Det er helt okay. Du kan altid skifte din mening ved at bruge burger-menuen nederst til venstre');
                $this->getBot()->typesAndWaits(3);
            } elseif (filter_var($answer->getText(), FILTER_VALIDATE_EMAIL)) {
                // valid address
                $usrCtr = new UserController();
                $usrCtr->addUserSubscription('ok', $answer->getText());
                $this->getBot()->typesAndWaits(2);
                $this->say('Coolio. Jeg sender en besked når der sker noget nyt.');
            } else {
                // invalid address
                $this->getBot()->typesAndWaits(2);
                $this->say('Undskyld det fik jeg ikke lige fat i. Prøv igen.');
                $this->subscriptionQuestion();
            }

        });
    }

    public function run()
    {
        $this->startSubConvo();
    }
}