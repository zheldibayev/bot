<?php

namespace App\Conversations;

use App\LastMessage;
use App\Product;
use BotMan\BotMan\Messages\Conversations\Conversation;

class AddTheProductConversation extends Conversation
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

    public function addTheProduct()
    {
        $lastMessage = LastMessage::where('conversation', 'product_' . $this->id)->orderBy('id', 'DESC')->first();
        $product = Product::where('id', $this->id)->get()->first();

        $cart = app('cart');

        $cart->addItem([
            'product_id'  => $product->id,
            'unit_price'  => $product->price,
            'quantity'    => 1

        ]);
        $carts = $cart->items()->where('product_id', $product->id)->get()->count();
        $number = $carts ? "(" . $carts . ")" : "";

        $inlineKeyboard = ['inline_keyboard' => [
            [
                ['text' => $product->price .' ₸ ' . $number, 'callback_data' => 'product_' . $product->id],

            ],
            [
                ['text' => '🛒 корзина' , 'callback_data' => 'cart_' . $product->id],
            ]

        ]];

        $response = $this->bot->sendRequest('editMessageReplyMarkup', [
            'message_id' => $lastMessage->message_id,
            'reply_markup' => json_encode($inlineKeyboard)
        ]);
        $message = json_decode($response->getContent());
        $messageId = $message->result->message_id;
        $chatId = $message->result->chat->id;

        LastMessage::create([
            'message_id' => $messageId,
            'chat_id' => $chatId,
            'conversation' => 'cart_' . $product->id
        ]);
    }

    public function run()
    {
        $this->addTheProduct();
    }
}
