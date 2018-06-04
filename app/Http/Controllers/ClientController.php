<?php

namespace App\Http\Controllers;

use App;

class ClientController extends Controller
{

    /**
     * Saves a new account to the database if they wish to sign up for any subscription.
     *
     * @param String $email
     * @param String $firstname
     * @param String $lastname
     * @param String $fb_id
     * @return mixed
     */
    public function saveNewClient(String $email, String $firstname, String $lastname, String $fb_id)
    {
        // TODO check if exists
        return $client = App\Client::create([
            'email' => $email,
            'subscribed' => True,
            'name' => $firstname . ' ' . $lastname,
            'facebook_id' => $fb_id
        ]);
    }

    /**
     * Unsubscribe a facebook user from email service, deleting the stored email.
     *
     * @param String $id
     */
    public function unsubscribe(String $id)
    {
        $client = App\Client::where("facebook_id", $id)->first();
        $client->email = "";
        $client->subscribed = False;
        $client->save();
    }

    /**
     * Checks if the provided id matches any in the database. Used to check whether a
     * facebook account is subscribed.
     *
     * @param String $id facebook_id to match
     * @return mixed matching client
     */
    public function checkSubscribed(String $id)
    {
        $client = App\Client::where("facebook_id", $id)->first();
        return $client;
    }
}
