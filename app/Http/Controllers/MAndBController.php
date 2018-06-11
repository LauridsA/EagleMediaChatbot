<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Input;

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
        $questionText = Input::post('QuestionText');
        return redirect('/ConversationBuilder')->with('status', 'you saved the button');
    }

    public function addMessage()
    {
            $questionText = Input::post('QuestionText');
        return redirect('/ConversationBuilder')->with('status', 'you saved the message');
    }

    public function removeButton()
    {

    }

    public function removeMessage()
    {

    }

    public function debug_to_console( $data ) {
        $output = $data;
        if ( is_array( $output ) )
            $output = implode( ',', $output);

        echo "<script>alert( 'Debug Objects: " . $output . "' );</script>";
    }
}
