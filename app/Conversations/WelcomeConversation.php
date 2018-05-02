<?php

namespace App\Conversations;

use App\Http\Controllers\TopicController;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Controllers\SubscriptionController;
use App\Message;

class WelcomeConversation extends Conversation
{
    public function welcomeMessage()
    {
        try {
            $message = Message::find(1);
            $this->getBot()->typesAndWaits($message['delay']);
            $this->say($message['message']);
        } catch (\Exception $exception) {
            echo 'Caught exception: ', $exception->getMessage(), '\n';
            $this->getBot()->typesAndWaits(2);
            $this->say('Beep beoop! Noget gik galt, så her er en fallback-besked i stedet.');
        } finally /* TODO finally block might make the bot run in exception state for the rest of lifetime */{
            $this->initialQuestion();
        }
    }

    public function initialQuestion()
    {
        $question = Question::create('Kasper, ejer af EagleMedia, arbejder med projekter og SaaS-løsninger. Inden vi fortsætter, vil jeg gerne stille et spørgsmål. Er det OK?')
            ->fallback('Unable to ask question')
            ->callbackId('initial_question')
            ->addButtons([
                Button::create('Ja da')->value('Ja'),
                Button::create('Aldrig!!')->value('Nej'),
                Button::create('get my ID ')->value('getID')
            ]);
        $this->ask($question, function (Answer $answer) {
            switch ($answer->getValue()){
                case 'Ja':
                    $subCtr = new SubscriptionController();
                    $subCtr->startSubscriptionConversation($this->getBot());
                    break;
                case 'Nej':
                    $this->say('Okay!');
                    $topicCtr = new TopicController();
                    $topicCtr->startTopicConversation($this->getBot());
//                    $this->bot->startConversation(new TopicConversation());
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

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->welcomeMessage();
    }

    public function endConversation()
    {
        $this->say('Tak for denne gang');
    }
}
