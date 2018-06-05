<?php

namespace App\Conversations;

use App\Http\Controllers\SubscriptionController;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Message;
use App\CustomButton;

class WelcomeConversation extends Conversation
{
    /**
     * This is the core of the chatbot conversation.
     * Recursively generates general questions from the database.
     *
     * @param $id message to load from the database
     */
    public function makeQuestion($id) // TODO fix bug where you have to write twice to get to ex. email sub convo
    {
        // If message is start of sub, else run normal builder
        if ($id === 5) {
            $ctr = new SubscriptionController();
            $ctr->startSubscriptionConversation($this->getBot());
        } else {
            $message = Message::find($id);
            $buttons = CustomButton::where('mid', $id)->get();

            $buttonArray = [];
            $responseArray = [];
            $buttonValues = [];


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

            // TODO Non-button answers should elicit an error, but keep asking for a response.
            // Fill the response array using button data
            foreach ($buttonValues as $button) {
                $responseArray[] =
                    [
                        'pattern' => $button['value'],
                        'callback' => function () use ($button) {
                            if (!is_null($button['next_message_id'])) {
                                $this->makeQuestion($button['next_message_id']);
                            } else {
                                $this->makeQuestion(2);
                            }
                        }
                    ];
            }

            // TODO add NLP functionality
            // Append responseArray to catch non-button messaging
            $responseArray[] = [
                'pattern' => '.*',
                'callback' => function (Answer $answer) {

                    $this->say("brug knapperne...");
                    $this->makeQuestion(2);
//                    if ($answer->getValue() == "EMAIL_PAYLOAD") {
////                        SubscriptionController::class . "@checkEmailStatus";
//                        $ctr = new SubscriptionController();
//                        $ctr->checkEmailStatus($this->getBot());
//                    } else {
//                        $this->say('Brug knapperne');
//                        $this->bot->typesAndWaits(2);
//                        $this->makeQuestion(2);
//                    }
                }
            ];

            // Create the question, add the buttons, and ready to receive answers
            $question = Question::create($message['message'])->addButtons($buttonArray);
            $this->ask($question, $responseArray);
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
