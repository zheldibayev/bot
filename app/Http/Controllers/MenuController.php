<?php

namespace App\Http\Controllers;

use App\Conversations\MenuConversation;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new MenuConversation());
    }


}
