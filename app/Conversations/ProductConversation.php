<?php

namespace App\Conversations;

use App\LastMessage;
use App\Product;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Log;

class ProductConversation extends Conversation
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

    public function askForProduct()
    {

        $keyboard = [
            ['🏠 Начало'],

        ];

        $this->bot->sendRequest("sendMessage", ["text" => 'Выберите продукт', 'reply_markup' => json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => false,
            'resize_keyboard' => true,
        ])
        ]);

        $products = Product::where('category_id', $this->id)->get();

        foreach ($products as $key => $product)
        {
            $cart = app('cart');

            $carts = $cart->items()->where('product_id', $product->id)->count();

            $number = $carts ? "(" . $carts . ")" : "";

            if(empty($carts)){
                $inlineKeyboard = ['inline_keyboard' => [
                    [
                        ['text' => $product->price . ' ₸ ', 'callback_data' => 'product_' . $product->id],

                    ]
                ]];
            } else {
                $inlineKeyboard = ['inline_keyboard' => [
                    [
                        ['text' => $product->price  .' ₸ ' . $number, 'callback_data' => 'product_' . $product->id],

                    ],
                    [
                        ['text' => '🛒 корзина' , 'callback_data' => 'cart_'. $product->id],
                    ]

                ]];
            }

//            $this->bot->channelStorage()->save([
//                'productId' => $product->id,
//                'categoryId' => $this->id,
//            ]);

            //  $this->bot->sendRequest('sendMessage', ['text' =>"<b>" . $product->name ."</b>" . "\n" . $product->description, 'parse_mode' => 'HTML']);

            $response = $this->bot->sendRequest('sendPhoto', [
                'photo' => config('urls.home') . '/images/' . $product->image,
                'caption' => $product->name . "\n" . $product->description,
                'reply_markup' => json_encode($inlineKeyboard)
            ]);

            $message = json_decode($response->getContent());
            $messageId = $message->result->message_id;
            $chatId = $message->result->chat->id;

            LastMessage::create([
                'message_id' => $messageId,
                'chat_id' => $chatId,
                'conversation' => 'product_' . $product->id
            ]);
        }
    }

    public function run()
    {
        $this->askForProduct();
    }
}
