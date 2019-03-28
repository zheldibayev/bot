<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use App\Client;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class MobileNumberConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */

    protected $bot_id;

    public function askForMobileNumber()
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


            $question = Question::create('Ваш мобильный телефон (Пример:87776665533):');

            $this->ask($question, function (Answer $answer) {
                if(preg_match("/^[0-9]{11}$/", $answer->getText())) {

                    $client = new Client();
                    $client->mobile = $answer->getText();
                    $client->bot_id = $this->getBot()->getUser()->getId();
                    $client->save();
                    $this->say('Спасибо, что внесли изменения');
                } else {
                    $this->say("Ошибка!
Укажите корректный мобильный номер.
Укажите верные данные:");
                    $this->repeat();
                }

            });
        } else {
            if(empty($client->mobile)) {
                $question = Question::create('Ваш мобильный телефон (Пример:87776665533):');

                $this->ask($question, function (Answer $answer) {
                    if(preg_match("/^[0-9]{11}$/", $answer->getText())) {

                        $client = Client::where('bot_id', $this->bot_id)->first();
                        $client->mobile = $answer->getText();
                        $client->save();
                        $this->say('Спасибо, что внесли изменения');
                    } else {
                        $this->say("Ошибка!
Укажите корректный мобильный номер.
Укажите верные данные:");
                        $this->repeat();
                    }

                });

            } else {

                $clients = Client::where('bot_id', $this->bot_id)->first();

                $question = Question::create('Ваш мобильный телефон (Пример:87776665533): ' . $clients->mobile ."\n".'Новое значение:');
                $this->ask($question, function (Answer $answer) {
                    if(preg_match("/^[0-9]{11}$/", $answer->getText())) {

                        $client = Client::where('bot_id', $this->bot_id)->first();

                        $client->mobile = $answer->getText();
                        $client->save();

                        $this->say('Спасибо, что внесли изменения');
                    } else {
                        $this->say("Ошибка!
Укажите корректный мобильный номер.
Укажите верные данные:");
                        $this->repeat();
                    }

                });
            }
        }


    }


    public function run()
    {
        $this->askForMobileNumber();
    }
}
