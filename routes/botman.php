<?php

use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    $bot->reply('Hej');
});
$botman->hears('YOUR_PAYLOAD_TEXT', BotManController::class . '@startConversation');
$botman->hears('Start', BotManController::class . '@startConversation');