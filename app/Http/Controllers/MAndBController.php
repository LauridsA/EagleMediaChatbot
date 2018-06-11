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

    public function addButton(Request $request)
    {
        $ButtonText = $request->input('ButtonText');
        $ButtonValue = $request->input('ButtonValue');
        $NextMessageID = $request->input('NextMessageID');
        $MID = $request->input('MID');
        $QuestionText = trim($ButtonText);
        $ButtonValue = trim($ButtonValue);
        $NextMessageID = trim($NextMessageID);
        $MID = trim($MID);
        if (empty($QuestionText) || empty($ButtonValue) || empty($NextMessageID) || empty($MID)){
            return redirect('/ConversationBuilder')->with('status', 'please fill all the fields');
        } else {
            $button = new App\CustomButton();
            $button->name = $ButtonText;
            $button->value = $ButtonValue;
            $button->mid = $MID;
            $button->next_message_id = $NextMessageID;
            $button->save();
            return redirect('/ConversationBuilder')->with('status', 'you saved the button');
        }
    }

    public function addMessage(Request $request)
    {
        $delay = $request->input('Delay');
        $QuestionText = $request->input('QuestionText');
        $delay = trim($delay);
        $QuestionText = trim($QuestionText);
        if (empty($delay) || empty($QuestionText)) {
            return redirect('/ConversationBuilder')->with('status', 'please set the inputs in the fields');
        } else {
            $message = new App\Message();
            $message->message = $QuestionText;
            $message->delay = $delay;
            $message->image = '';
            $message->save();
            return redirect('/ConversationBuilder')->with('status', 'you saved the message');
        }
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
