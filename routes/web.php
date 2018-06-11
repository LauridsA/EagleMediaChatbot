<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/ConversationBuilder', function () {
   return view('ConversationBuilder');
});
Route::post('ajax', "MAndBController@removeButton");
Route::post('button',"MAndBController@addButton");
Route::post('message',"MAndBController@addMessage");
Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');
