<?php

namespace App\Conversations;

use App\Client;
use App\Order;
use App\Product;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Hassansin\DBCart\Facades\Cart;
use Illuminate\Support\Facades\Log;

class ConfirmConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    public function askForConfirm()
    {
        $cart = app('cart');
        //Log::info($this->bot->channelStorage()->get('userCartsId'));
        $bot_id = $this->bot->getUser()->getId();
        $client = Client::where('bot_id', $bot_id)->first();
        $client_id = $client->id;
        $items = $cart->items()->get();

       // Log::info('items ' . $items);
        foreach ($items as $item) {
            $order = new Order();
            $order->card_id = $item->cart_id;
            $order->client_id = $client_id;
            $order->product_id = $item->product_id;
            $order->quantity = $item->quantity;
            $order->save();
            $cart->complete();

        }

      //  $books = Order::where('client_id', $client_id)->orderBy('created_at', 'desc')->first();

        $keyboard = [

            ['🏠 Начало']
        ];

        $this->bot->sendRequest("sendMessage", ["text" => 'Спасибо что сделали заказ', 'reply_markup' => json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => false,
            'resize_keyboard' => true,
        ])
        ]);


    }

    public function run()
    {
        $this->askForConfirm();
    }
}
