<?php

use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    $bot->reply('Hej');
});
$botman->hears('YOUR_PAYLOAD_TEXT', function ($bot) {
    $bot->reply('payload recieved');
});
$botman->hears('Start', BotManController::class . '@startConversation');