<?php

namespace App\Conversations;

use App\Category;

use App\DBStorage;
use App\LastMessage;
use App\Product;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
//use Darryldecode\Cart\Cart;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Hassansin\DBCart\Models\CartLine;
use Illuminate\Support\Facades\Session;
use Hassansin\DBCart\Models\Cart;



class CategoryConversation extends Conversation
{
    /**
     * First question
     */


    public $cart;

    public function __construct()
    {
        $this->cart = app('cart');
    }

    public function askReason()
    {

        //$this->say($subCategories);
        //  foreach ($subCategories as $subCategory) {

        //   if ($answer->getValue() == $subCategory->title) {

        //    $subCat = Category::whereTitle($answer->getValue())->firstOrFail()->get();
        //       foreach ($subCat as $sub) {
        //           $this->say($sub->id);
        //    }


        //    $question1 = Question::create("Выберите подкатегорию")
        //        ->fallback('Unable to ask question')
        //        ->callbackId('ask_reason')
        //        ->addButton(Button::create('Обувь')->value('Обувь'));

        //   return $this->ask($question1, function (Answer $answer) {
        //      $this->say('подкатегория мужские');

        //    });

        //  }
        // }

    }

    public function getCategory()

    {
        $categories = Category::whereNull('parent_id')->get();

        $buttons = [];

        foreach ($categories as $category) {
            $buttons[] = Button::create("$category->title")->value($category->id);

        }

        $question = Question::create("Выберите категорию")
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons($buttons);

        return $this->ask($question, function (Answer $answer) {

            if ($answer->isInteractiveMessageReply()) {

                $this->getSubCategory($answer);
            } else {
                $this->repeat();
            }
        });
    }



    public function getSubCategory(Answer $answer)

    {
   //     $subCategoryId = Category::whereTitle($answer->getValue())->first()->id;

        $subCategory = Category::where('parent_id', $answer->getValue())->get();

       /* $buts = [];

        foreach($subCategory as $sub) {
            $buts[] = Button::create($sub->title)->value($sub->id);

        }*/

        $subCategoryQuestion = Question::create("Выберите под категорию")
            ->fallback('Unable to ask question');

        foreach($subCategory as $sub) {
            $subCategoryQuestion->addButtons([Button::create($sub->title)->value($sub->id)]);

        }

        return $this->ask($subCategoryQuestion, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {

                $this->getProduct($answer);

            } else {
                $this->repeat();
            }

        });
    }


    public function getProduct(Answer $answer)
    {

        $subCategoryId = $answer->getValue();
        $products = Product::where('category_id', $subCategoryId)->get();

      //  $this->say($products);

        foreach ($products as $product) {

            $images = Image::url('https://6288f2f7.ngrok.io/images/' . $product->image);

            $message = OutgoingMessage::create("$product->name")->withAttachment($images);
            $this->say($message);

          //  $carts = $this->cart->items()->get();
            $carts = $this->cart->items()->where('product_id', $product->id)->get()->count();
            $number = $carts ? "( $carts )" : "";

            if(empty($carts)) {

                $keyboard = Keyboard::create()
                    ->type(Keyboard::TYPE_INLINE)
                    ->addRow(KeyboardButton::create("$product->price тенге $number")
                        ->callbackData("$product->id $product->price"))
                    ->toArray();
            } else {
                $keyboard = Keyboard::create()
                    ->type(Keyboard::TYPE_INLINE)
                    ->addRow(KeyboardButton::create("$product->price тенге $number")
                        ->callbackData("$product->id $product->price"))
                    ->addRow(KeyboardButton::create('Корзина')->callbackData('Корзина'))
                    ->toArray();
            }

            $this->getBot()->reply('sadasdasd', $keyboard);

      /*      $q = Question::create("$product->description")
                ->addButtons($keyboards); */

        /*     $this->ask($q, function (Answer $answer){
                if ($answer->isInteractiveMessageReply()) {


                    $piece = $answer->getValue();
                    $pieces = explode(" ", $piece);
                    $this->cart->addItem([
                        'product_id'  => $pieces[0],
                        'unit_price'  => $pieces[1],
                        'quantity'    => 1

                   ]);


               }


            });*/


        }

    }


    public function keyboard()
    {
        return Keyboard::create()
            ->type(Keyboard::TYPE_INLINE)
            ->addRow(KeyboardButton::create('hello')->callbackData('next'))
            ->toArray();
    }


    /**
     * Start the conversation
     */
    public function run()
    {


       $this->ask('kkkkk', function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {

                $inlineKeyboard = ['inline_keyboard' => [
                    [
                        ['text' => 'New text', 'callback_data' => 'New text'],

                    ]

                ]];

                switch ($answer->getValue()) {

                case 'next':
                    $parameters = [
                        'chat_id' => $answer->getMessage()->getPayload()['chat']['id'],
                        'message_id' => $answer->getMessage()->getPayload()['message_id'],

                        'reply_markup' => json_encode($inlineKeyboard),

                    ];
                    $this->bot->sendRequest('editMessageReplyMarkup', $parameters);

                }


            }


        }, $this->keyboard());
//       $this->getCategory();
    }


}
