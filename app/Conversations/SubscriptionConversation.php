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
        $responseArray = [];
        $buttonValues = [];
        foreach ($buttons as $button) {
            $buttonArray[] = Button::create($button['name'])->value($button['value']);
        }
        foreach ($buttons as $button) {
            $buttonValues[] = [
                'name' => $button['name'],
                'value' => $button['value'],
                'mid' => $button['mid'],
                'next_message_id' => $button['next_message_id']
            ];
        }
        foreach ($buttonValues as $button) {
            $responseArray[] =
                [
                    'pattern' => $button['value'],
                    'callback' => function () use ($button) {
                        if (true) { //($button['next_message_id'] == 7) TODO should not be true
                            $this->subscription($button['next_message_id']);
                        } else if ($button['name'] == 'Afbryd') {
                        $this->say('fag');
                        $ctr = new BotManController();
                        $ctr->startConversation($this->getBot());
                        }
                    }
                ];
        }
        $question = Question::create($message['message'])->addButtons($buttonArray);
        $this->ask($question, $responseArray);
    }

    public function subscription($id)
    {
        try {
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
                //TODO dette bliver aldrig anvendt... why? crasher
                if (filter_var($answer->getText(), FILTER_VALIDATE_EMAIL)) {
                    $ctr = new ClientController();
                    $newClient = $ctr->saveNewClient($answer->getText(), $this->bot->getUser()->getFirstName(), $this->bot->getUser()->getLastName());
                    $this->say('Din email er blevet registreret som: ' . $newClient['email']);
                    $ctr = new BotManController();
                    $ctr->startConversation($this->getBot());
                }
            });
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
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