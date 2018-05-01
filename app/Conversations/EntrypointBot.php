<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Controllers\FAQControllerConversation;
use App\Http\Controllers\SaaSControllerConversation;
use App\Http\Controllers\ProjectControllerConversation;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Message;
use BotMan\Drivers\Facebook\FacebookDriver;

class EntrypointBot extends Conversation
{
    public function welcomeMessage()
    {
        $message = Message::find(1);
        $this->getBot()->typesAndWaits($message['delay']);
        $this->say($message['message']);
    }

    public function initialQuestion()
    {
        $question = Question::create('Kasper, ejer af EagleMedia, arbejder med projekter og SaaS-løsninger. Inden vi fortsætter, vil jeg gerne stille et spørgsmål. Er det OK?')
            ->fallback('Unable to ask question')
            ->callbackId('initial_question')
            ->addButtons([
                Button::create('Ja da')->value('Ja'),
                Button::create('Aldrig!!')->value('Aldrig'),
                Button::create('get my ID ')->value('getID')
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
                case 'getID':
                    $sender = $this->bot->getMessage()->getPayload();
                    $this->say($sender);
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
                    $this->getBot()->typesAndWaits(2);
                    $ctr = new SubscriptionController();
                    //$this->bot->getMessage()->setText('pause');
                    //$this->skipsConversation($this->bot->getMessage()); pause holder kun 1 request. derefter resume.
                    $ctr->startSubscriptionConversation($this->getBot(), $this->bot->getUser()->getId()); // kommer ind i class OK
                    // fortsætter direkte. dårlig design? ny convo? hears? what to do..
                    //$this->topicQuestion();
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
        $this->say('Tak for denne gang');
    }
}
