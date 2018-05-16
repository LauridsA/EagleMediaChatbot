<?php

use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    $bot->reply('Hej');
});
$botman->hears('Kom i gang', BotManController::class . '@startConversation');
$botman->hears('Get Started', BotManController::class . '@startConversation');
$botman->hears('Start', BotManController::class . '@startConversation');