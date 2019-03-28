<?php

namespace App\Conversations;

use App\Category;
use App\LastMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class MenuConversation extends Conversation
{


    public function getMenu()
    {

        $keyboard = [
            ['🏠 Начало'],

        ];

        $this->bot->sendRequest("sendMessage", ["text" => 'Меню', 'reply_markup' => json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => false,
            'resize_keyboard' => true,
        ])
        ]);

        $categories = Category::whereNull('parent_id')->get();

        $cats  = [];

        foreach ($categories as $key => $category)
        {
            // это сумма подкатегорий
            //  $catTotal = Category::withCount('products')->where('parent_id', $category->id)->get()->sum('products_count');

            $cats[$key] = [];

            $cats[$key][] = [
                'text' => $category->title,  //. ' (' . $catTotal . ') ',
                'callback_data' => 'category_' . $category->id,
            ];

        }

        $inlineKeyboard = [
            'inline_keyboard' => $cats,
        ];

        $response = $this->bot->sendRequest('sendMessage', [
            'text' => 'Нажмите на раздел и выберите блюдо',
            'reply_markup' => json_encode($inlineKeyboard)
        ]);

        $message = json_decode($response->getContent());
        $messageId = $message->result->message_id;
        $chatId = $message->result->chat->id;

        LastMessage::create([
            'message_id' => $messageId,
            'chat_id' => $chatId,
            'conversation' => 'menu_categories'
        ]);

    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->getMenu();
    }
}
