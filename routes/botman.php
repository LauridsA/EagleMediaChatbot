<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\BotMan;

$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    $user = $bot->getUser();
    $name = $user->getFirstName();
    $bot->reply($name.'');
    //$email = $user->getEmail();
    $bot->reply('Hej');
});
$botman->hears('GET_STARTED_NEW_USER', function (BotMan $bot) {
    $bot->reply('Made it to GET_STARTED...');
    $ctr = new BotManController();
    $ctr->startConversation($bot);
});
$botman->hears('Start', BotManController::class.'@startConversation');