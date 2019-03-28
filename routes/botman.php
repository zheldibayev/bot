<?php
use App\BotgramConversation;
//use App\Category;
//use App\Conversations\CategoryConversation;
//use App\Conversations\ExampleConversation;
//use App\Http\Controllers\BotManController;
//use App\LastMessage;
//use BotMan\BotMan\Messages\Attachments\Location;
//use BotMan\BotMan\Messages\Conversations\Conversation;
//use BotMan\BotMan\Messages\Incoming\Answer;
//use BotMan\BotMan\Messages\Outgoing\Actions\Button;
//use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Botman\BotMan;
//use BotMan\Drivers\Telegram\Extensions\Keyboard;
//use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
//use Illuminate\Support\Facades\Log;
//use App\Product;
//use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
//use BotMan\BotMan\Messages\Attachments\Image;
//use Hassansin\DBCart\Models\Cart;
//use Hassansin\DBCart\Models\CartLine;
//use App\Http\Middleware\TypingMiddleware;



$botman = resolve('botman');

//$typingMiddleware = new TypingMiddleware();
//$botman->middleware->sending($typingMiddleware);

$botman->fallback(function($bot) {
    $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: 1-Hi, 2-How are you, 3-Show my id');
});

$botman->hears('🍴 Меню|/menu', function(BotMan $bot) {

    $bot->startConversation(new \App\Conversations\MenuConversation());

})->skipsConversation();

$botman->hears('category_([0-9]+)', function(BotMan $bot, $id) {

    $bot->startConversation(new \App\Conversations\SubCategoryConversation($id));

})->skipsConversation();

$botman->hears('subcategory_([0-9]+)', function (BotMan $bot, $id) {

    $bot->startConversation(new \App\Conversations\ProductConversation($id));

})->skipsConversation();

$botman->hears('product_([0-9]+)', function(BotMan $bot, $id) {

    $bot->startConversation(new \App\Conversations\AddTheProductConversation($id));

})->skipsConversation();

//$botman->hears('След 1', function(BotMan $bot) {
//
//    $bot->startConversation(new \App\Conversations\NextFiveProductsConversation());
//
//})->skipsConversation();


$botman->hears('❓ Помощь', function (BotMan $bot){
    $bot->startConversation(new App\Conversations\SupportConversation());
})->skipsConversation();

$botman->hears('📞 Позвонить', function (BotMan $bot) {
    $bot->startConversation(new App\Conversations\CallBackConversation());
})->skipsConversation();

$botman->hears('✉ Написать', function (BotMan $bot) {
    $bot->startConversation(new App\Conversations\LeaveMessageConversation());
})->skipsConversation();

$botman->hears('📘 Помощь на сайте', function(BotMan $bot) {
   $bot->startConversation(new App\Conversations\HelpDeskConversation());
})->skipsConversation();

$botman->hears('🏠 Начало|/start', function (BotMan $bot) {

    $keyboard = [
        ['🍴 Меню', '🛍 Корзина'],
        ['📦 Заказы', '📢 Новости'],
        ['⚙️ Настройки','❓ Помощь']
    ];

    $bot->sendRequest("sendMessage", ["text" => 'Добро пожаловать', 'reply_markup' => json_encode([
        'keyboard' => $keyboard,
        'one_time_keyboard' => false,
        'resize_keyboard' => true,
    ])
    ]);
})->skipsConversation();

$botman->hears('⚙️ Настройки|⬅ Назад', function (BotMan $bot) {
    $bot->startConversation(new App\Conversations\SettingConversation());
})->skipsConversation();

$botman->hears('👤 Имя', function(Botman $bot) {
    $bot->startConversation(new App\Conversations\NameConversation());
})->skipsConversation();

$botman->hears('📲 Моб.|Тел', function(Botman $bot) {
    $bot->startConversation(new App\Conversations\MobileNumberConversation());
})->skipsConversation();

$botman->hears('🗺 Адрес', function(Botman $bot) {
    $bot->startConversation(new App\Conversations\AddressConversation());
})->skipsConversation();

$botman->hears('🏢 Город', function(Botman $bot) {
    $bot->startConversation(new App\Conversations\CityConversation());
})->skipsConversation();

$botman->hears('📬 Почта', function(Botman $bot) {
    $bot->startConversation(new App\Conversations\EmailConversation());
})->skipsConversation();

$botman->hears('📡 Час. пояс', function (BotMan $bot){
    $bot->startConversation(new \App\Conversations\LocationConversation());
})->skipsConversation();

$botman->hears('Еще', function (BotMan $bot) {
    $bot->startConversation(new \App\Conversations\NextNewsConversation());
})->skipsConversation();

$botman->hears('📢 Новости', function (BotMan $bot) {
    $bot->startConversation(new \App\Conversations\NewsConversation());
})->skipsConversation();
//
//$botman->hears('🏃 Заберу сам|🚖 Привезти', function (BotMan $bot) {
//    $bot->startConversation(new \App\Conversations\PickUpConversation());
//})->skipsConversation();
//
//$botman->hears('⌚ Как можно скорее', function (BotMan $bot) {
//    $bot->startConversation(new \App\Conversations\DeliveryTimeConversation());
//})->skipsConversation();

$botman->hears('✅ Подтвердить и оформить', function (BotMan $bot) {
    $bot->startConversation(new \App\Conversations\ConfirmConversation());
})->skipsConversation();

$botman->hears('📦 Заказы|/history', function (BotMan $bot) {
    $bot->startConversation(new \App\Conversations\OrdersConversation());
})->skipsConversation();

//$botman->hears('Как можно скорее|Укажу время', function (BotMan $bot) {
//    $bot->startConversation(new \App\Conversations\DeliveryTimeConversation());
//})->skipsConversation();

//$botman->hears('🛍 Корзина', function (BotMan $bot) {
//    $bot->startConversation(new \App\Conversations\BusketConversation());
//
//});


$botman->hears('🛍 Корзина', function (BotMan $bot) {
    $bot->startConversation(new App\Conversations\BusketConversation());
})->skipsConversation();

$botman->hears('checkout|Предыдующая', function (BotMan $bot) {
    $bot->startConversation(new \App\Conversations\CheckoutConversation());
})->skipsConversation();

$botman->hears('stop', function(BotMan $bot) {
    $bot->reply('stopped');
})->stopsConversation();




