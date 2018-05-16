<?php

namespace App\Conversations;

use App\Http\Controllers\BotManController;
use App\Http\Controllers\ClientController;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Message;
use App\CustomButton;

class SubscriptionConversation extends Conversation
{


    public function subscriptionQuestion($id)
    {
        $message = Message::find($id);
        $buttons = CustomButton::where('mid', $id)->get();
        $buttonArray = [];
        foreach ($buttons as $button) {
            $buttonArray[] = Button::create($button['name'])->value($button['value']);
        }
        $question = Question::create($message['message'])->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer, $buttons) {
            if ($answer->getValue() == $buttons[1]['value']) {
                $this->say('fag');
                $ctr = new BotManController();
                $ctr->startConversation($this->getBot());
            }
            if ($answer->getValue() == $buttons[0]['value']) {
                $this->subscription(7);
            }
        });
    }

    public function subscription($id)
    {
        $message = Message::find($id);
        $buttons = CustomButton::where('mid', $id)->get();
        $buttonArray = [];
        foreach ($buttons as $button) {
            $buttonArray[] = Button::create($button['name'])->value($button['value']);
        }

        $question = Question::create($message['message'])->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer, $buttons) {
            if ($answer->getValue() == $buttons[0]['value']) {
                $this->say('fag');
                $ctr = new BotManController();
                $ctr->startConversation($this->getBot());
            }

            if (filter_var($answer->getText(), FILTER_VALIDATE_EMAIL)) {
                $ctr = new ClientController();
                $newClient = $ctr->saveNewClient($answer->getText());
                $this->say('Din email er blevet registreret som: ' . $newClient['email']);
                $ctr = new BotManController();
                $ctr->startConversation($this->getBot());
            }
        });

    }

    /**
     * @return mixed
     */
    public function run()
    {
        $this->subscriptionQuestion(5);
        // $this->IsThisYourMail(6) // TODO email suggestion with button
    }
}