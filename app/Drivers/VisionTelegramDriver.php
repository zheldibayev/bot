<?php
namespace App\Drivers;

use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Support\Facades\Log;


class VisionTelegramDriver extends TelegramDriver {

	public function messagesHandled() {
		Log::info('MESSAGE HANDLED');
	}
}