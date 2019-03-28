<?php

namespace App\Conversations;

use App\Client;
use App\Order;
use App\Product;
use BotMan\BotMan\Messages\Conversations\Conversation;

class OrdersConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */


    public function askForOrders()
    {
       // $clientId = $this->bot->channelStorage()->get('client_id');
        //$productId = $this->bot->channelStorage()->get('id');

        $cart = app('cart');

        $bot_id = $this->bot->getUser()->getId();

        $client = Client::whereId('bot_id', $bot_id)->first();

      //  $product = Product::whereId($productId)->first();

       // $botId = $this->bot->getUser()->getId();

        // временно поменял bot_id на client_id

        $books = Order::with(['client', 'product'])->where('client_id', $client->id)->orderBy('id', 'desc')->get();

        if(empty($client)) {
            $this->say('Вы ещё ничего не заказывали 😬
Посмотрите наше /menu');
        }

        foreach ($books as $book) {

            $message = "-------------------------------------- \n";
            $message .= "Имя : " . $book->client->name . "\n";
            //  $message .= 'Email : ' . $user->get('email') . '<br>';
            $message .= "Номер телефона : " . $book->client->mobile . "\n";
            $message .= "Название услуг : " . $book->product->name . "\n";
            $message .= 'Дата-заказа : ' . $book->created_at . "\n";
            // $message .= 'Time : ' . $user->get('timeSlot') . '<br>';
            $message .= "-------------------------------------";
            $this->say("Ваши заказы. \n \n" . $message);

        }
    }
    public function run()
    {
        $this->askForOrders();
    }
}
