<?php

use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    $bot->reply('Hej');
});
$botman->hears('GET_STARTED_NEW_USER', function ($bot) {
    $bot->reply('what');
});
$botman->hears('Start', BotManController::class . '@startConversation');