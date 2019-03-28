<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Client;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class EmailConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    protected $bot_id;

    public function askForEmail()
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


            $question = Question::create('Ваш электронный адрес (Пример: thevision@gmail.com):');

            $this->ask($question, function (Answer $answer) {
                if(filter_var($answer->getText() ,  FILTER_VALIDATE_EMAIL)){
                    $client = new Client();
                    $client->email = $answer->getText();
                    $client->bot_id = $this->getBot()->getUser()->getId();
                    $client->save();
                    $this->say('Спасибо, что внесли изменения');
                } else {
                    $this->say("Ошибка!
Укажите корректный электронный адрес.
Укажите верные данные:");
                    $this->repeat();
                }



            });
        } else {
            if(empty($client->email)) {
                $question = Question::create('Ваш электронный адрес (Пример: thevision@gmail.com):');

                $this->ask($question, function (Answer $answer) {
                    if(filter_var($answer->getText() ,  FILTER_VALIDATE_EMAIL)){

                    $client = Client::where('bot_id', $this->bot_id)->first();
                    $client->email = $answer->getText();
                    $client->save();
                    $this->say('Спасибо, что внесли изменения');
                    } else {
                        $this->say("Ошибка!
Укажите корректный электронный адрес.
Укажите верные данные:");
                        $this->repeat();
                    }
                });

            } else {

                $clients = Client::where('bot_id', $this->bot_id)->first();

                $question = Question::create('Ваш электронный адрес (Пример: thevision@gmail.com):' . $clients->email ."\n".'Новое значение:');
                $this->ask($question, function (Answer $answer) {
                    if(filter_var($answer->getText() ,  FILTER_VALIDATE_EMAIL)){


                        $client = Client::where('bot_id', $this->bot_id)->first();

                    $client->email = $answer->getText();
                    $client->save();

                    $this->say('Спасибо, что внесли изменения');
                    } else {
                        $this->say("Ошибка!
Укажите корректный электронный адрес.
Укажите верные данные:");
                        $this->repeat();
                    }
                });
            }
        }


    }

    public function run()
    {
        $this->askForEmail();
    }
}
