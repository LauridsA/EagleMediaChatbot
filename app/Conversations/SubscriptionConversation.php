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
                        if ($button['value'] == 'subByEmail') {
                            $this->subscription($button['next_message_id']);
                        } else if ($button['value'] == 'cancel') {
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

            $question = Question::create($message['message'])->addButtons($buttonArray);
            $this->ask($question, function (Answer $answer) use ($buttonValues) {
                if ($answer->getText() == 'cancel') {
                    $ctr = new BotManController();
                    $ctr->startConversation($this->getBot());
                } else {
                    if (filter_var($answer->getText(), FILTER_VALIDATE_EMAIL)) {
                        $ctr = new ClientController();
                        $newClient = $ctr->saveNewClient($answer->getText(), 'laurids', 'simonsen'); // TODO $this->bot->getUser()->getFirstName(), $this->bot->getUser()->getLastName()
                        $this->say('Din email er blevet registreret som: ' . $newClient['email']);
                        $ctr = new BotManController();
                        $ctr->startConversation($this->getBot());
                    } else {
                        $this->say('Det ser ud til, at der er noget galt med din email. Prøv igen!');
                        $this->subscription(7);
                    }
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