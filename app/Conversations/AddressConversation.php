<?php

namespace App\Conversations;

use App\Client;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class AddressConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    protected $bot_id;

    public function askForAddress()
    {
        $this->bot_id = $this->bot->getUser()->getId();
        $client = Client::where('bot_id', $this->bot_id)->first();
        $keyboard = [

            ['🏠 Начало' , '⬅ Назад']
        ];

        $this->bot->sendRequest("sendMessage", ["text" => 'Вы в разделе личного кабинета', 'reply_markup' => json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => false,
            'resize_keyboard' => true,
        ])
        ]);


        if(empty($client)) {


            $question = Question::create('Пожалуйста введите ваш адрес:');

            $this->ask($question, function (Answer $answer) {

                $client = new Client();
                $client->address = $answer->getText();
                $client->bot_id = $this->getBot()->getUser()->getId();
                $client->save();
                $this->say('Спасибо, что внесли изменения');

            });
        } else {
            if(empty($client->address)) {
                $question = Question::create('Пожалуйста введите ваш адрес:');

                $this->ask($question, function (Answer $answer) {

                    $client = Client::where('bot_id', $this->bot_id)->first();
                    $client->address = $answer->getText();
                    $client->save();
                    $this->say('Спасибо, что внесли изменения');

                });

            } else {

                $clients = Client::where('bot_id', $this->bot_id)->first();

                $question = Question::create('Ваш Адрес: ' . $clients->address ."\n".'Новое значение:');
                $this->ask($question, function (Answer $answer) {

                    $client = Client::where('bot_id', $this->bot_id)->first();

                    $client->address = $answer->getText();
                    $client->save();

                    $this->say('Спасибо, что внесли изменения');

                });
            }
        }


    }

    public function run()
    {
        $this->askForAddress();
    }
}
