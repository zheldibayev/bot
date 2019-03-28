<?php

namespace App\Conversations;

use App\LastMessage;
use App\Product;
use BotMan\BotMan\Messages\Conversations\Conversation;

class AddToCartConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    public $id;
    public $price;

    public function __construct($id, $price)
    {
        $this->id = $id;
        $this->price = $price;
    }

    function getCartNextId($pages, $id) {

        $pos = 0;

        foreach ($pages as $key => $value) {
            if ($value == $id) $pos = $key;
        }

        if ($pos >= count($pages)- 1) return $pages[0];
        return $pages[$pos + 1];
    }

    function getCartPrevId($pages, $id) {
        $pos = 0;

        foreach ($pages as $key => $value) {
            if ($value == $id) $pos = $key;
        }

        if ($pos <= 0) return $pages[count($pages) - 1];
        return $pages[$pos - 1];
    }

    public function askForAddingToCart()
    {
        $cart = app('cart');
        $cart->addItem([
            'product_id'  => $this->id,
            'unit_price'  => $this->price,
            'quantity'    => 1

        ]);
        $lastMessage = LastMessage::where('conversation', 'add_product_' . $this->id . '_' . $this->price )->orderBy('id', 'DESC')->first();

        //$lastDescriptionMessage = LastMessage::where('conversation', 'product_description' )->orderBy('id', 'DESC')->first();

      //  $lastPriceMessage = LastMessage::where('conversation', 'product_price'  )->orderBy('id', 'DESC')->first();

        $product = Product::whereId($this->id)->first();

        // first or get?
        $items = $cart->items()->get()->unique('product_id');

        $totalProductQuantity = $cart->items()->where('product_id', $this->id)->get()->count();


        $pages = [];

        foreach ($items as $key=>$value) {
            $pages[] = $value->product_id;
        }

        $nextId = $this->getCartNextId($pages, $product->id);
        $prevId = $this->getCartPrevId($pages, $product->id);

        $nextButtonRow =  ['text' => '▶' , 'callback_data' => 'next_product_' . $nextId ];
        $prevButtonRow =  ['text' => '◀' , 'callback_data' => 'prev_product_' . $prevId ];

        $num = array_search($product->id, $pages)+1;

        $cartInlineKeyboard = ['inline_keyboard' => [
            [
                ['text' => '❌', 'callback_data' => 'delete_' . $product->id],
                ['text' => '🔻' , 'callback_data' => 'remove_one_product_' . $product->id . '_'. $product->price],
                ['text' => $totalProductQuantity . " шт." , 'callback_data' => 'quantity'],
                ['text' => '🔺' , 'callback_data' => 'add_product_' . $product->id . '_'. $product->price],

            ],
            [
                $prevButtonRow,
                ['text' => $num . '/' . $items->count() , 'callback_data' => 'total'],
                $nextButtonRow,
            ],
            [
                ['text' => '✅ Оформить заказ?' , 'callback_data' => 'checkout'],

            ]

        ]];

//        $bot->sendRequest('editMessageText', [
//            'message_id' => $lastDescriptionMessage->message_id,
//            'chat_id'    => $lastDescriptionMessage->chat_id,
//            'text'      => $product->description . 'edited'
//
//        ]);
//        $bot->sendRequest('editMessageText', [
//            'chat_id'    => $lastPriceMessage->chat_id,
//            'message_id' => $lastPriceMessage->message_id,
//            'text'       => "$product->price" . "*" . "$totalProductQuantity = " . $product->price * $totalProductQuantity .' тенге'
//
//        ]);

        $response = $this->bot->sendRequest('editMessageText', [
            'message_id' => $lastMessage->message_id,
            'chat_id'    => $lastMessage->chat_id,
            'parse_mode' =>'HTML',
            'text' =>"<b>". $product->name . "</b>" ."\n" .$product->description . "\n" . "$product->price" . "*" . "$totalProductQuantity = " . $product->price * $totalProductQuantity .' тенге' .'<a href="' . config('urls.home').'/images/' . $product->image .' ">​​​​​​​​​​​</a>',

            'reply_markup' => json_encode($cartInlineKeyboard)
        ]);

        $message = json_decode($response->getContent());
        $messageId = $message->result->message_id;
        $chatId = $message->result->chat->id;

        LastMessage::create([
            'message_id' => $messageId,
            'chat_id' => $chatId,
            'conversation' => 'add_product_' . $product->id . '_'. $product->price
        ]);

        LastMessage::create([
            'message_id' => $messageId,
            'chat_id' => $chatId,
            'conversation' => 'delete_' . $product->id
        ]);


        LastMessage::create([
            'message_id' => $messageId,
            'chat_id' => $chatId,
            'conversation' => 'prev_product_' .  $prevId
        ]);

        LastMessage::create([
            'message_id' => $messageId,
            'chat_id' => $chatId,
            'conversation' => 'next_product_' .  $nextId
        ]);
        LastMessage::create([
            'message_id' => $messageId,
            'chat_id' => $chatId,
            'conversation' => 'remove_one_product_' . $product->id . '_'. $product->price
        ]);

    }

    public function run()
    {
        $this->askForAddingToCart();
    }
}
