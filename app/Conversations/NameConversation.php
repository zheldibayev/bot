<?php

namespace App\Conversations;

use App\Client;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Log;

class NameConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    protected $bot_id;

    public function askForName()
    {
        $this->bot_id = $this->bot->getUser()->getId();
        $client = Client::where('bot_id', $this->bot_id)->first();
        $keyboard = [

            ['🏠 Начало' , '⬅ Назад']
        ];

        $this->bot->sendRequest("sendMessage", ["text" => 'Вы в разделе личного кабинета', 'reply_markup' => json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => true,
            'resize_keyboard' => true,
        ])
        ]);


        if(empty($client)) {


            $question = Question::create('Пожалуйста введите ваше имя:');

            $this->ask($question, function (Answer $answer) {

                $client = new Client();
                $client->name = $answer->getText();
                $client->bot_id = $this->getBot()->getUser()->getId();
                $client->save();
                $this->say('Спасибо, что внесли изменения');

            });
        } else {
            if(empty($client->name)) {
                $question = Question::create('Пожалуйста введите ваше имя:');

                $this->ask($question, function (Answer $answer) {

                    $client = Client::where('bot_id', $this->bot_id)->first();
                    $client->name = $answer->getText();
                    $client->save();
                    $this->say('Спасибо, что внесли изменения');

                });

            } else {

                $clients = Client::where('bot_id', $this->bot_id)->first();

                $question = Question::create('Ваше имя: ' . $clients->name ."\n".'Новое значение:');
                $this->ask($question, function (Answer $answer) {

                    $client = Client::where('bot_id', $this->bot_id)->first();

                    $client->name = $answer->getText();
                    $client->save();

                    $this->say('Спасибо, что внесли изменения');

                });
            }
        }


    }

    public function run()
    {
        $this->askForName();
    }
}
