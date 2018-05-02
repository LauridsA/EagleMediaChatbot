<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hej', function ($bot) {
    $uname =  $bot->getUser()->getUsername();
    $fname =  $bot->getUser()->getFirstName();
    file_put_contents('PHP_ERROR_LOG.txt', $uname, FILE_APPEND);
    file_put_contents('PHP_ERROR_LOG.txt', $fname, FILE_APPEND);
    foreach ($bot->getUser()->getInfo() as $info){
        file_put_contents('PHP_ERROR_LOG.txt', $info . '\n', FILE_APPEND);
    }
    $bot->reply('Hej');
});
$botman->hears('Start', BotManController::class.'@startConversation');