<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class CallBackConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->say('Горячая линия "Bot"
+7 (778)564-66-27');
    }
}
