<?php

namespace App\Conversations;

use App\Http\Controllers\ClientController;
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
        if(strpos($message['message'], 'tilmelde dig' !== false)) {
            $this->subscription($id);
        }
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
                        if ($button['interest_trigger'] != null){
                            $this->logInterest($button['interest_trigger'], $this->bot->getUser()->getId());
                        }
                        if ($button['next_message_id'] != null)
                        $this->makeQuestion($button['next_message_id']);
                    }
                ];
        }
        $responseArray[] = [
            'pattern' => '.com|.dk|.se|.no|.de|.co.uk',
            'callback' => function (Answer $answer) {

            }
        ];
        $responseArray[] = [
            'pattern' => '.*',
            'callback' => function (Answer $answer) {
                if($answer->getText() == ''){
                    $this->say('did not click button');
                }
                else {
                    $this->say('you said '.$answer->getText());
                }
            }
        ];
        $question = Question::create($message['message'])->addButtons($buttonArray);
        $this->ask($question, $responseArray);
    }

    public function logInterest($interest, $user_id) {
        $ctr = new ClientController();
        $ctr->saveNewInterest($interest, $user_id);
    }

    public function subscription($id) {
        $message = Message::find($id);
        $buttons = CustomButton::where('mid', $id)->get();
        $buttonArray = [];
        foreach ($buttons as $button) {
            $buttonArray[] = Button::create($button['name'])->value($button['value']);
        }

        $question = Question::create($message['message'])->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer, $buttons) {
            if($answer->getValue() == $buttons[0]['value']){
                $this->say('fag');
                $this->makeQuestion(2);
            }
            if (filter_var($answer->getText(), FILTER_VALIDATE_EMAIL)) {
                $ctr = new ClientController();
                $newClient = $ctr->saveNewClient($answer->getText());
                $this->say('Din email er blevet registreret som: ' . $newClient['email']);
                $this->makeQuestion(2);
            }
        });
    }
}
