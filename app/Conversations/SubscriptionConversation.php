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

                        try {
                            $ctr = new ClientController();
                            $newClient = $ctr->saveNewClient($answer->getText(), $this->bot->getUser()->getFirstName() . '', $this->bot->getUser()->getLastName() . ''); // TODO $this->bot->getUser()->getFirstName(), $this->bot->getUser()->getLastName()
                            $this->say('Din email er blevet registreret som: ' . $newClient['email']);
                        } catch (Exception $ex) {
                            Bugsnag::notifyException($ex);
                        }
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

    public function checkEmailStatus(string $id){
        $ctr = new ClientController();
        $client = $ctr->checkSubscribed($id);
        $subbed = $client->subscribed;
        if($subbed == 'ok'){
            $this->say('Din mail er blevet fundet til at være'. $client->email. '. Du er sat til at få nyhedsbreve.');
            $this->unSubscriptionQuestion(10);
        } else if ($subbed == 'Not ok') {
            $this->say('Din mail er blevet fundet til at være'. $client->email. '. Du er sat til ikke at få nyhedsbreve.');
            $this->subscriptionQuestion(5);
        } else {
            $this->say('Din mail blev ikke fundet i vores database.');
        }
    }


    /**
     * @return mixed
     */
    public function run()
    {
        $this->checkEmailStatus($this->bot->getUser()->getId());
        // $this->IsThisYourMail(6) // TODO email suggestion with button
    }

    public function unSubscriptionQuestion($id)
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
                if ($answer->getValue() == 'cancel') {
                    $ctr = new BotManController();
                    $ctr->startConversation($this->getBot());
                } else {
                    if ($answer->getValue() == 'unsub') {
                        try {
                            $ctr = new ClientController();
                            $ctr->unsubscribe($this->bot->getUser()->getId());
                            $this->say('Du er blevet afmeldt nyhedsbrevet!');
                        } catch (Exception $ex) {
                            Bugsnag::notifyException($ex);
                        }
                        $ctr = new BotManController();
                        $ctr->startConversation($this->getBot());
                    }

                }

            });
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }