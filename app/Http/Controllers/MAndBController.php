<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class MAndBController extends Controller
{
    public function getMessagesData()
    {
        $messages = App\Message::all('id');
        return $messages;
    }

    public function getMessagesAllData()
    {
        $messages = App\Message::all();
        return $messages;
    }

    public function getButtonData(String $mid)
    {
        $buttons = App\CustomButton::where('mid', $mid)->get();
        return $buttons;
    }

    public function addButton()
    {

    }

    public function addMessage()
    {

    }

    public function removeButton()
    {

    }

    public function removeMessage()
    {

    }
}
