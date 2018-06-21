<?php

namespace App\Conversations;

use App\Client;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\SubscriptionController;
use App\User;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Message;
use App\CustomButton;
use App\InterestTrigger;
use App\UserInterest;

class WelcomeConversation extends Conversation
{
    /**
     * This is the core of the chatbot conversation.
     * Recursively generates general questions from the database.
     *
     * @param $id message to load from the database
     */
    public function makeQuestion($id)
    {
        $this->saveInterest($id);
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

            // Append to the end of the $responseArray
            $responseArray[] = [
                'pattern' => '.*',
                'callback' => function (Answer $answer) {
                    if ($answer->getText() == "Afmeld opdateringer") {
                        $ctr = new SubscriptionController();
                        $ctr->checkBroadcastStatus($this->getBot());
                    } elseif ($answer->getText() == "Tilmeld Nyhedsbrev (email)") {
                        $ctr = new SubscriptionController();
                        $ctr->checkEmailStatus($this->getBot());
                    } elseif ($answer->getText() == "Tilmed opdateringer") {
                        $ctr = new SubscriptionController();
                        $ctr->checkBroadcastStatus($this->getBot());
                    } elseif ($answer->getText() == "Afmeld Nyhedsbrev (email)") {
                        $ctr = new SubscriptionController();
                        $ctr->checkEmailStatus($this->getBot());
                    } else {
                        $ctr = new BotManController();
                        $ctr->startConversation($this->getBot());
                    }
                }
            ];

            // Create the question, add the buttons, and ready to receive answers
            if (isset($message['delay'])) {
                if (is_int($message['delay']))
                    $this->bot->typesAndWaits($message['delay']);
            } else {
                $this->bot->typesAndWaits(2);
            }
            $question = Question::create($message['message'])->addButtons($buttonArray);
            $this->ask($question, $responseArray);
        }
    }

    /**
     * Save Interest based on message
     *
     * @param $id message ID used to to load interest from the database
     */
    public function saveInterest($id)
    {
        try {
            $interest_trigger = InterestTrigger::where('message_id', $id)->first();
            $client = Client::where('facebook_id', $this->bot->getUser()->getId())->first();
            $interest_id = $interest_trigger['interest_id'];
            $user_id = $client['id'];
            if (isset($interest_id) && isset($user_id)){
                $user_interest_prev = UserInterest::where('user_id', $user_id)->where('interest_id', $interest_id)->first();
                if (isset($user_interest_prev)){
                    return;
                }
                $user_interest = new UserInterest();
                $user_interest->interest_id = $interest_id;
                $user_interest->user_id = $user_id;
                $user_interest->save();
            }
        } catch (\Exception $e) {
            return;
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
