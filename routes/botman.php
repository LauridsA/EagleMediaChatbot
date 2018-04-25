<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    $bot->reply('Hej');
});
$botman->hears('Start', BotManController::class.'@startConversation');