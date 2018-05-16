<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    $user = $bot->getUser();
    $name = $user->getFirstName();
    $bot->reply($name.'');
    //$email = $user->getEmail();
    $bot->reply('Hej');
});
$botman->hears('Start', BotManController::class.'@startConversation');