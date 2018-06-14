<?php

namespace App\Conversations;

use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Message;
use App\CustomButton;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use App\Http\Controllers\SubscriptionController;

class BroadcastConversation extends Conversation
{

    public function subToBroadcast($id)
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
                if ($answer->getValue() == $buttonValues[0]['value']) {
                    $this->say('Fedt! :) Jeg skriver dig op! (Du kan altid ændre din mening senere');
                    $ctr = new SubscriptionController();
                    $ctr->addUserToLabel((string)$this->bot->getUser()->getId());
                    $ctr = new BotManController();
                    $ctr->startConversation($this->getBot());
                } else if ($answer->getValue() == $buttonValues[1]['value']) {
                    $this->say('Okay! :) Du kan altid skifte mening senere!');
                    $ctr = new BotManController();
                    $ctr->startConversation($this->getBot());
                }
            });
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }

    }

    public function unSubToBroadcast($id)
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
                if ($answer->getValue() == $buttonValues[0]['value']) {
                    $ctr = new SubscriptionController();
                    $ctr->removeUserFromLabel((string)$this->bot->getUser()->getId());
                    $ctr = new BotManController();
                    $ctr->startConversation($this->getBot());
                } else if ($answer->getValue() == $buttonValues[1]['value']) {
                    $this->say('Okay. Du vil stadig få beskeder herinde.');
                    $ctr = new BotManController();
                    $ctr->startConversation($this->getBot());
                }
            });
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }


    }

    public function checkBroadcastStatus()
    {
        $ctr = new SubscriptionController();
        $theid = (string)$this->bot->getUser()->getId();
        $subbed = $ctr->retrieveLabel($theid);
            if ($subbed) {
                $this->say('Du er sat op til at modtage beskeder herinde.');
                $this->unSubToBroadcast(8);
            } else if (!$subbed) {
                $this->say('Du er sat op til ikke at modtage beskeder herinde.');
                $this->subToBroadcast(9);
            } else {
                $this->say('you broke it');
            }
    }

    public function run()
    {
        $this->checkBroadcastStatus();
    }
}