<?php

namespace App\Conversations;

use App\Category;
use App\LastMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;

class SubCategoryConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function run()
    {
        $lastMessage = LastMessage::where('conversation', 'menu_categories')->orderBy('id', 'DESC')->first();

        $subcategories = Category::where('parent_id', $this->id)->get();

        //$subcats = [];

        $subcats  = [];

        foreach ($subcategories as $key => $subcategory)
        {
            $subcats[$key] = [];

            $subcats[$key][] = [
                'text' => $subcategory->title,  //. ' (' . $catTotal . ') ',
                'callback_data' => 'subcategory_' . $subcategory->id,
            ];

            // общее количество блюд в каждом категорий
           //  $catTotal = Category::withCount('products')->where('id', $subcategory->id)->get()->sum('products_count');

            // ниже указанный код для того чтобы вывести меню ввиде колонки
            //
            //            if (!isset($subcats[$row])) $subcats[$row] = [];
            //
            //            $subcats[$row][] = [
            //                'text' => $subcategory->title, //. ' (' . $catTotal . ') ',
            //                'callback_data' => 'subcategory_' . $subcategory->id,
            //            ];
            //
            //            if (($key + 1) % 2 == 0) $row++;
        }

            // back menu button
           // $subcats[][] = ['text' => 'В начало меню', 'callback_data' => 'back_menu'];

        $inlineKeyboard = [
            'inline_keyboard' => $subcats,
        ];

        $response = $this->bot->sendRequest('editMessageReplyMarkup', [
            'message_id' => $lastMessage->message_id,
            'chat_id'    => $lastMessage->chat_id,
            'reply_markup' => json_encode($inlineKeyboard)
        ]);

        $message = json_decode($response->getContent());
        $messageId = $message->result->message_id;
        $chatId = $message->result->chat->id;

        LastMessage::create([
            'message_id' => $messageId,
            'chat_id' => $chatId,
            'conversation' => 'menu_subcategories'
        ]);
    }
}
