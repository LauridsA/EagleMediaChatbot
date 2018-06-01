<?php

use App\Http\Controllers\BotManController;
use App\Http\Controllers\SubscriptionController;


$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    $fag = $bot->getMessage()->getPayload();
    foreach ($fag as $blind){
        $bot->reply(''.$blind);
    }
    });

$botman->hears('GET_STARTED_PAYLOAD', BotManController::class . '@startConversation');
$botman->hears('Start', BotManController::class . '@startConversation');
$botman->hears('Nyheder', SubscriptionController::class . '@checkBroadcastStatus');
$botman->hears('UPDATES_PAYLOAD', SubscriptionController::class . '@checkBroadcastStatus');
