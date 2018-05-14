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
                    'callback' => function (Answer $answer) use ($button) {
                        if ($answer->isInteractiveMessageReply()) {

                            $this->say('Is button: ' . $answer->isInteractiveMessageReply());
                            $this->makeQuestion($button['next_message_id']);
                        } else {
//                            $this->say('Is button: ' . $answer->isInteractiveMessageReply());
                            $this->say("faggot");
                        }
                    }
                ];
        }

        $question = Question::create($message['message'])->addButtons($buttonArray);
        $this->ask($question, $responseArray);
    }
}
