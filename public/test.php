<?php
echo 'Hello world!';
//use FacebookSubscription;
require_once('..\App\FacebookSubscription.php');


class BroadcastMessage {

    public function retrieveLabel(string $id)
    {
        $access_token = getenv('FACEBOOK_TOKEN');
        $api_url = 'https://graph.facebook.com/v2.11/' . $id . '/custom_labels?access_token=' . $access_token;
        $cSession = curl_init($api_url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        $retrieved = curl_exec($cSession);
        curl_close($cSession);
        if (strpos($retrieved, 'id') == false) {
            $res = 0;
        } else {
            $res = 1;
        }
        return $res;
    }
}

class Broadcast {

    public function addToLabel(string $id){
        $addJSON = '{"user":' . $id . '}';
        $labelID = "2439019392790895";
        $access_token = getenv('FACEBOOK_TOKEN');
        $api_url = 'https://graph.facebook.com/v2.11/' . $labelID . '/label?access_token=' . $access_token;
        $cSession = curl_init($api_url);
        curl_setopt($cSession, CURLOPT_POST, 1);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_POSTFIELDS, $addJSON);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $added = curl_exec($cSession);
        curl_close($cSession);
        return $added;
    }
}

$something = new BroadcastMessage();
$somethingnew = new Broadcast();
//echo $somethingnew->addToLabel("1678248702231382");
echo $something->retrieveLabel("1678248702231382");
