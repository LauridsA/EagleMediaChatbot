<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class MAndBController extends Controller
{
    public function getMessagesData()
    {
        $messages = App\Client::all();
        return $messages;
    }

    public function getButtonData()
    {
        $buttons = App\CustomButton::all();
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
