<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class SettingConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $keyboard = [
            ['👤 Имя', '📲 Моб.', '🗺 Адрес'],
            ['🏢 Город', '📡 Час. пояс', '📬 Почта'],
            ['🏠 Начало']
        ];

        $this->bot->sendRequest("sendMessage", ["text" => 'Выберите настройки, которые хотите поменять:', 'reply_markup' => json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => true,
            'resize_keyboard' => true,
        ])
        ]);
    }
}
