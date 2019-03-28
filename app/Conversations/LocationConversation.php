<?php

namespace App\Conversations;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Location;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Illuminate\Support\Facades\Log;
use App\Client;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class LocationConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */


    protected $bot_id;

    public function askLocation()
    {
        $this->bot_id = $this->bot->getUser()->getId();
        $client = Client::where('bot_id', $this->bot_id)->first();
        $keyboard = [[['text' => 'Отправить моё месторасположение', 'request_location' => true]


        ],['🏠 Начало','⬅ Назад']];

        $this->bot->sendRequest("sendMessage", ["text" => 'Отправьте локацию', 'reply_markup' => json_encode([
            'keyboard' => $keyboard,
            'one_time_keyboard' => true,
            'resize_keyboard' => true,
        ])
        ]);


        if(empty($client)) {


            $question = Question::create('Отправьте ваше месторасположение:');

            $this->askForLocation($question, function (Location $location) {

                $client = new Client();
                $client->longitude = $location->getLongitude();
                $client->latitude = $location->getLatitude();
                $client->bot_id = $this->getBot()->getUser()->getId();
                $client->save();
                $this->say('Спасибо, что внесли изменения');

            });
        } else {
            if(empty($client->longitude) || empty($client->latitude)) {
                $question = Question::create('Отправьте ваше месторасположение:');

                $this->askForLocation($question, function (Location $location) {


                    $client = Client::where('bot_id', $this->bot_id)->first();
                    $client->longitude = $location->getLongitude();
                    $client->latitude = $location->getLatitude();
                    $client->save();
                    $this->say('Спасибо, что внесли изменения');

                });

            } else {

                $clients = Client::where('bot_id', $this->bot_id)->first();

                $json = file_get_contents("http://api.geonames.org/timezoneJSON?lat=".$clients->latitude."&lng=".$clients->longitude."&username=zheldibayev");
                $data = json_decode($json);

                $question = Question::create('Отправьте Ваши координаты, чтобы определить часовой пояс: ' . $data->timezoneId ."\n".'Новое значение:');
                $this->askForLocation($question, function (Location $location) {



                    $client = Client::where('bot_id', $this->bot_id)->first();

                    $client->longitude = $location->getLongitude();
                    $client->latitude = $location->getLatitude();
                    $client->save();

                    $this->say('Спасибо, что внесли изменения');

                });
            }
        }


    }



//    public function askLocations()
//    {
//        $keyboard = [[['text' => 'Отправить моё месторасположение', 'request_location' => true]
//
//
//        ],['🏠 Начало','⬅ Назад']];
//
//        $this->bot->sendRequest("sendMessage", ["text" => 'Отправьте локацию', 'reply_markup' => json_encode([
//            'keyboard' => $keyboard,
//            'one_time_keyboard' => true,
//            'resize_keyboard' => true,
//        ])
//        ]);
//
//
//        $this->askForLocation('Отправьте Ваши координаты, чтобы определить часовой пояс:', function (Location $location) {
//            $this->say($location->getLongitude(). '        ' . $location->getLatitude());
//        });
//
//        $this->getBot()->receivesLocation(function(BotMan $bot) {
//            $data = $bot->getMessage()->getLocation();
//            $this->say($data->getLatitude(). '    ' . $data->getLongitude());
//        });

//        $this->ask('Show phone', function (Answer $answer)  {
//            $this->bot->reply('Handled!');
//        }, ['reply_markup' => json_encode([
//            'keyboard' => [[['text' => 'отправить телефон', 'request_location' => true]]]
//        ])]);

//    }

    public function run()
    {
        $this->askLocation();
    }
}
