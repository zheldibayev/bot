<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class SupportConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
//        $this->say("
//
//");

        $keyboard = [
            ['📞 Позвонить', '✉ Написать'],
            ['📘 Помощь на сайте'],
            ['🏠 Начало']
        ];

        $this->bot->sendRequest("sendMessage", ["text" => '
        Список команд:
/menu — Меню
/cart — Корзина
/history — История заказов
/news — Наши новости и акции
/settings — Настройки
/help — Справка
/about — О проекте
/start — Главное меню
/stop - Остановить Диалог

Выберите ниже раздел справки и получите краткую помощь. Если Ваш вопрос не решен, обратитесь за помощью к живому оператору zheldibayev@gmail.com.', 'reply_markup' => json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => true,
            'resize_keyboard' => true,
        ])
        ]);
    }



}
