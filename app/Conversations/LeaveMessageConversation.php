<?php

namespace App\Conversations;

use App\LeaveMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class LeaveMessageConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    public function askForMessage()
    {
        $question = Question::create('Напишите сообщение. Оно будет отправлено команде 
"The Vision":')->fallback('отправьте сообщение заново');

        $this->ask($question, function(Answer $answer) {

            $saveTheMessage = new LeaveMessage();

            $saveTheMessage->body = $answer->getText();

            $saveTheMessage->bot_id = $this->bot->getUser()->getId();

            $saveTheMessage->save();

            $this->say('Сообщение успешно отправлено!
Мы рассмотрим обращение и свяжемся с Вами. ');
            });
    }

    public function run()
    {
        $this->askForMessage();
    }
}
