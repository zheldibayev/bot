<?php

namespace App\Conversations;

use App\Discount;
use App\LastMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class NewsConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    public function askForNews()
    {

        $news = Discount::all()->last();

        $key =  Keyboard::create()
            ->type(Keyboard::TYPE_KEYBOARD)->resizeKeyboard(true)->oneTimeKeyboard(false)
            ->addRow(KeyboardButton::create('🏠 Начало')->callbackData('/menu'),
                KeyboardButton::create('Еще'))
            ->toArray();

        $this->say('Добро пожаловать!',$key);

        $createdAt = Carbon::parse($news->created_at);
        $suborder['payment_date'] = $createdAt->format('d.m.Y' . ' год');


        $this->bot->sendRequest("sendPhoto", ['photo' => config('urls.home') . '/images/' . $news->image ]);
        $this->bot->sendRequest("sendMessage", ['text' => $suborder['payment_date']]);
        $this->bot->sendRequest("sendMessage", ['text' => $news->title . "\n" . $news->body]);

        $this->bot->channelStorage()->save([
            'newsId' => $news->id
        ]);

        }

    public function run()
    {
        $this->askForNews();
    }
}
