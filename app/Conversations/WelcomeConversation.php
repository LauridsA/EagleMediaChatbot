<?php

namespace App\Conversations;

use App\Http\Controllers\ClientController;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Message;
use App\CustomButton;

class WelcomeConversation extends Conversation
{
    public function makeQuestion($id)
    {
        $message = Message::find($id);
        $buttons = CustomButton::where('mid', $id)->get();

        $buttonArray = [];
        $responseArray = [];
        $buttonValues = [];

        // check if message is subscription
        if (strpos($message['message'], 'tilmelde dig' !== false)) {
            $this->subscription($id);
        }

        // Create a button for each button found for a message
        foreach ($buttons as $button) {
            $buttonArray[] = Button::create($button['name'])->value($button['value']);
        }

        // Create a manageable array for button values
        foreach ($buttons as $button) {
            $buttonValues[] = [
                'name' => $button['name'],
                'value' => $button['value'],
                'mid' => $button['mid'],
                'next_message_id' => $button['next_message_id']
            ];
        }

        // TODO: Non-button answers should elicit an error, but keep asking for a response.
        // Fill the response array using button data
        foreach ($buttonValues as $button) {
            $responseArray[] =
                [
                    'pattern' => $button['value'],
                    'callback' => function () use ($button) {
                        if ($button['interest_trigger'] != null) {
                            $this->logInterest($button['interest_trigger'], $this->bot->getUser()->getId());
                        }
                        if ($button['next_message_id'] != null)
                            $this->makeQuestion($button['next_message_id']);
                    }
                ];
        }

        // Append responseArray to catch non-button messaging
        $responseArray[] = [
            'pattern' => '.*',
            'callback' => function (Answer $answer) {
                if (trim($answer->getText()) == '') {
                    $this->say('skriv noget din nar');
                    $this->makeQuestion(2);
                } else {
                    $this->say('du skrev: ' . $answer->getText());
                    $this->makeQuestion(2);
                }
            }
        ];

        // Create the question, add the buttons, and ready to receive answers
        $question = Question::create($message['message'])->addButtons($buttonArray);
        $this->ask($question, $responseArray);


        $question = Question::create($message['message'])->addButtons($buttonArray);
        $this->ask($question, function (Answer $answer, $buttons) {
            if ($answer->getValue() == $buttons[0]['value']) {
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

    public function logInterest($interest, $user_id)
    {
        $ctr = new ClientController();
        $ctr->saveNewInterest($interest, $user_id);
    }

    public function subscription($id)
    {
        $message = Message::find($id);
        $buttons = CustomButton::where('mid', $id)->get();
        $buttonArray = [];
        foreach ($buttons as $button) {
            $buttonArray[] = Button::create($button['name'])->value($button['value']);
        }


    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->makeQuestion(2);
    }


}
