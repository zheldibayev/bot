<?php

namespace App\Conversations;

use App\Discount;
use App\LastMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class NextNewsConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    public $id;

    public function askForNextNews()
    {

        $keyboard = [

            ['🏠 Начало' , 'Еще']
        ];

        $this->bot->sendRequest("sendMessage", ["text" => 'Последние новости', 'reply_markup' => json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => false,
            'resize_keyboard' => true,
        ])
        ]);

        $this->id = $this->bot->channelStorage()->get('newsId');

        if($this->previous()) {
            $this->bot->channelStorage()->save([
                'newsId' => $this->previous()->id
            ]);
            $createdAt = Carbon::parse($this->previous()->created_at);
            $suborder['date'] = $createdAt->format('d.m.Y' . ' год');


            $this->bot->sendRequest("sendPhoto", ['photo' => config('urls.home') . '/images/' . $this->previous()->image ]);
            $this->bot->sendRequest("sendMessage", ['text' => $suborder['date']]);
            $this->bot->sendRequest("sendMessage", ['text' => $this->previous()->title . "\n" . $this->previous()->body]);
        } else {

            $keyboard = [

                ['🏠 Начало']
            ];

            $this->bot->sendRequest("sendMessage", ["text" => 'Больше новостей нет', 'reply_markup' => json_encode([
                'keyboard' => $keyboard,
                'one_time_keyboard' => false,
                'resize_keyboard' => true,
            ])
            ]);
        }
    }

    public function previous()
    {

        return Discount::where('id', '<', $this->id)->orderBy('id', 'desc')->first();
    }

    public function run()
    {
        $this->askForNextNews();
    }
}
