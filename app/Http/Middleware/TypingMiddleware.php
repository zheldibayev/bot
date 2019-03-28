<?php

namespace App\Http\Middleware;

use BotMan\BotMan\BotMan;

use BotMan\BotMan\Interfaces\Middleware\Sending;

use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class TypingMiddleware implements Sending
{
    /**
     * Handle an outgoing message payload before/after it
     * hits the message service.
     *
     * @param mixed $payload
     * @param BotMan $bot
     * @param $next
     *
     * @return mixed
     */
    public function sending($payload, $next, BotMan $bot)
    {
        $bot->typesAndWaits(1);

        return $next($payload);
    }
}
