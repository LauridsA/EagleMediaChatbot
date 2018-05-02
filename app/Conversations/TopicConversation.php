<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class TopicConversation extends Conversation
{
    public function topicQuestion()
    {
        $question = Question::create('Hvad kunne du tænke dig at snakke om?')
            ->fallback('Unable to ask question')
            ->callbackId('topic_question')
            ->addButtons([
                Button::create('Projekter')->value('Projekter'),
                Button::create('SaaS løsninger')->value('SaaS'),
                Button::create('Typiske spørgsmål')->value('FAQ'),
                Button::create('Hvad hugger i af mine data?')->value('data'),
            ]);
        $this->ask($question, function (Answer $answer) {
            switch ($answer->getValue()){
                case 'Projekter':
                    $this->say('NYI');
                    $this->topicQuestion();
                    break;
                case 'SaaS':
                    // call SAAS CTR + conversation
                    $this->say('NYI');
                    $this->topicQuestion();
                    break;
                case 'FAQ':
                    // call FAQ CTR + conversation
                    $this->say('NYI');
                    $this->topicQuestion();
                    break;
                case 'data':
                    $this->say('ALT. VI EJER DIG NU');
                    break;
                default:
                    $this->say('hvad? Brug helst knapperne ....');
                    $this->topicQuestion();
                    break;
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->topicQuestion();
    }

    public function endConversation()
    {
        $this->say('Tak for denne gang');
    }
}
