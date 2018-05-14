<?php

namespace App\Conversations;

use App\Http\Controllers\TopicController;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Http\Controllers\SubscriptionController;
use App\Message;
use App\CustomButton;

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
        } finally /* TODO finally block might make the bot run in exception state for the rest of lifetime */ {
            $this->initialQuestion();
        }
    }

    public function initialQuestion()
    {
        $button = CustomButton::where('mid', 1)->get();
        // TODO are questions saved differently in DB? some hardcoding seems nessesary... message + button relation also
        $question = Question::create('Kasper, ejer af EagleMedia, arbejder med projekter og SaaS-løsninger. Inden vi fortsætter, vil jeg gerne stille et spørgsmål. Er det OK?')
            ->fallback('Unable to ask question')
            ->callbackId('initial_question')
            ->addButtons([ // TODO would be great with a foreach, does not accept it though ... :/
                Button::create($button[0]['name'])->value($button[0]['value']),
                Button::create($button[1]['name'])->value($button[1]['value']),
                Button::create('get my ID ')->value('getID')
            ]);
        $this->ask($question, function (Answer $answer) {
            $button = CustomButton::where('mid', 1)->get();
            switch ($answer->getValue()) {
                case $button[0]['value']:
                    $this->subQuestion();
                    break;
                case $button[1]['value']:
                    $this->say('Okay!');
                    $topicCtr = new TopicController();
                    $topicCtr->startTopicConversation($this->getBot());
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

    public function subQuestion()
    {
        $question = Question::create('Kunne du tænke dig, at modtage beskeder her på facebook om nyheder osv? Vi skriver maks en gang i ugen, det lover vi!')
            ->callbackId('subQuestion')
            ->addButtons([
                Button::create('Ja da')->value('Ja'),
                Button::create('Aldrig!!')->value('Nej')
            ]);
        $this->ask($question, function (Answer $answer) {
            switch ($answer->getValue()) {
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
                default:
                    $this->say('hvad? Brug helst knapperne ....');
                    $this->subQuestion();
                    break;
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->makeQuestion(2);
    }

    public function endConversation()
    {
        $this->say('Tak for denne gang');
    }

    public function makeQuestion($id)
    {
        $message = Message::find($id);
        $buttons = CustomButton::where('mid', $id)->get();
        $buttonArray = [];
        foreach ($buttons as $button) {
            $buttonArray[] = Button::create($button['name'])->value($button['value']);
        }

        $buttonValues = [];
        foreach ($buttons as $button) {
            $buttonValues[] = [
                'name' => $button['name'],
                'value' => $button['value'],
                'mid' => $button['mid'],
                'next_message_id' => $button['next_message_id']
            ];
        }

        $responseArray = [];
        foreach ($buttonValues as $button) {
            $responseArray[] =
                [
                    'pattern' => $button['value'],
                    'callback' => function () use ($button) {
                        $this->makeQuestion($button['next_message_id']);
                    }
                ];
        }

        $question = Question::create($message['message'])->addButtons($buttonArray);
        $this->ask($question, $responseArray);
    }
}
