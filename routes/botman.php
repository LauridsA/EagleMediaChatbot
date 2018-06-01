<?php

use App\Http\Controllers\BotManController;
use App\Http\Controllers\SubscriptionController;


$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    //$id = $bot->getUser()->getId();
    //$bot->reply('your id is: ' . $id);
    $bot->reply('Hej');
});
$botman->hears('Kom i gang', BotManController::class . '@startConversation');
$botman->hears('Get Started', BotManController::class . '@startConversation');
$botman->hears('Start', BotManController::class . '@startConversation');
$botman->hears('Nyheder', SubscriptionController::class . '@checkBroadcastStatus');
