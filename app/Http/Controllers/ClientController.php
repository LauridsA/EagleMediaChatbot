<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class ClientController extends Controller
{
    public function saveNewClient(String $email, String $firstname, String $lastname, String $fb_id)
    {
        // save new client to db and return it to caller. Needs all fillable fields set it seems.
        // TODO check if exists
        return $client = App\Client::create(['email'=>$email, 'subscribed'=>'ok', 'name'=>$firstname.' '.$lastname, 'facebook_id'=>$fb_id]);
    }

    public function saveNewInterest($interest, $user_id) {
        return $result = App\User_Interest::create(['interest_id' => $interest, 'user_id' => $user_id]);
//        $user = User::firstOrNew(array('name' => Input::get('name'))); TODO check if exists
//        $user->foo = Input::get('foo');
//        $user->save();
    }

    public function unsubscribe(String $id){
        $client = App\Client::find($id);
        $client->subscribed = 'Not ok';
        $client->save();
    }

    /**
     * Checks if the provided id matches any in the database. Used to check whether or not a facebook
     *
     * @param String $id
     * @return mixed
     */
    public function checkSubscribed(String $id) {
//        $client = App\Client::find($id);
        $client = App\Client::where("facebook_id", $id)->first();
        return $client;
    }
}
