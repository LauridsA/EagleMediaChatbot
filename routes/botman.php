<?php

use App\Http\Controllers\BotManController;
use App\Http\Controllers\SubscriptionController;


$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {$bot->reply('Hej');});
$botman->hears('GET_STARTED_PAYLOAD', BotManController::class . '@startConversation');
$botman->hears('Start', BotManController::class . '@startConversation');
$botman->hears('Nyheder', SubscriptionController::class . '@checkBroadcastStatus');
$botman->hears('UPDATES_PAYLOAD', SubscriptionController::class . '@checkBroadcastStatus');
