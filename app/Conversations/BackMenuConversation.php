<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class BackMenuConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */



    public function run()
    {
//        $lastMessage = LastMessage::where('conversation', 'menu_categories')->orderBy('id', 'DESC')->first();
//
//        $categories = Category::whereNull('parent_id')->get();
//        $cats  = [];
//
//        foreach ($categories as $key => $category)
//        {
//            $cats[$key] = [];
//
//            $cats[$key][] = [
//                'text' => $category->title . ' (' . $category->items_count . ') ',
//                'callback_data' => 'category_' . $category->id,
//            ];
//        }
//
//        $inlineKeyboard = [
//            'inline_keyboard' => $cats,
//        ];
//
//        $response = $bot->sendRequest('editMessageReplyMarkup', [
//            'message_id' => $lastMessage->message_id,
//            'reply_markup' => json_encode($inlineKeyboard)
//        ]);
//        // Log::info($response->getContent());
//
//        $message = json_decode($response->getContent());
//        $messageId = $message->result->message_id;
//        $chatId = $message->result->chat->id;
//
//        LastMessage::create([
//            'message_id' => $messageId,
//            'chat_id' => $chatId,
//            'conversation' => 'menu_subcategories'
//        ]);

    }
}
