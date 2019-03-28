<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class HelpDeskConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->getBot()->sendRequest('sendMessage', [
            'text' => 'Подробная справка на сайте:' . "\n" .'<a href="http://thevision.kz">The vision - официальный сайт</a>',
            'parse_mode' => 'HTML'
        ]);
    }
}
