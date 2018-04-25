<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Controllers\FAQControllerConversation;
use App\Http\Controllers\SaaSControllerConversation;
use App\Http\Controllers\ProjectControllerConversation;
use App\Http\Controllers\UserController;

class EntrypointBot extends Conversation
{
    public function welcomeMessage()
    {
        $this->say('Bip bop, jeg er en bot! (Vær sød ved mig ..) Velkommen hos EagleMedia!');
    }

    public function initialQuestion()
    {
        $question = Question::create('Kasper, ejer af EagleMedia, arbejder med projekter og SaaS-løsninger. Inden vi fortsætter, vil jeg gerne stille et spørgsmål. Er det OK?')
            ->fallback('Unable to ask question')
            ->callbackId('initial_question')
            ->addButtons([
                Button::create('Ja da')->value('Ja'),
                Button::create('Aldrig!!')->value('Aldrig'),
            ]);
        $this->ask($question, function (Answer $answer) {
            switch ($answer->getValue()){
                case 'Ja':
                    $this->subscriptionQuestion();
                    break;
                case 'Aldrig':
                    $this->say('Okay!');
                    $this->topicQuestion();
                    break;
                default:
                    $this->say('hvad? Brug helst knapperne ....');
                    $this->initialQuestion();
                    break;
            }
        });
    }

    public function subscriptionQuestion()
    {
        $question = Question::create('Kunne du tænke dig at modtage nyheder om webshops eller SaaS løsninger her? Maks en gang i ugen, det lover vi!')
            ->fallback('Unable to ask question')
            ->callbackId('subscription_question')
            ->addButtons([
                Button::create('Ja da')->value('Ja'),
                Button::create('Aldrig!!')->value('Aldrig'),
            ]);
        $this->ask($question, function (Answer $answer) {
            switch ($answer->getValue()){
                case 'Ja':
                    $userController = new UserController();
                    $userController->addUserSubscription("ok");
                    $this->getBot()->typesAndWaits(2);
                    $this->say('Fedt! Jeg skriver til dig når der er nyheder'); // TODO subscription conversation?
                    $this->topicQuestion();
                    break;
                case 'Aldrig':
                    $this->say('Okay! Du kan altid skifte din mening ved at bruge burger-menuen nederst til venstre');
                    $this->topicQuestion();
                    break;
                default:
                    $this->say('hvad? Brug helst knapperne ....');
                    $this->subscriptionQuestion();
                    break;
            }
        });
    }

    public function topicQuestion()
    {
        $question = Question::create('Hvad kunne du tænke dig at snakke om?')
            ->fallback('Unable to ask question')
            ->callbackId('topic_question')
            ->addButtons([
                Button::create('Projekter')->value('Projekter'),
                Button::create('SaaS løsninger')->value('SaaS'),
                Button::create('Typiske spørgsmål')->value('FAQ'),
                Button::create('Hvad hugger i af mine data?')->value('data'),
            ]);
        $this->ask($question, function (Answer $answer) {
            switch ($answer->getValue()){
                case 'Projekter':
                    $projectCTR = new ProjectControllerConversation();
                    $projectCTR->startConversation();
                    break;
                case 'SaaS':
                    // call SAAS CTR + conversation
                    break;
                case 'FAQ':
                    // call FAQ CTR + conversation
                    break;
                case 'data':
                    $this->say('ALT. VI EJER DIG NU');
                    break;
                default:
                    $this->say('hvad? Brug helst knapperne ....');
                    $this->topicQuestion();
                    break;
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->welcomeMessage();
        $this->initialQuestion();
    }
    public function endConversation()
    {
        $this->say('Tak for denne gang, fag!');
    }
}
