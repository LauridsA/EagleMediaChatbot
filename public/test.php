<?php
echo 'Hello world!';
//use FacebookSubscription;
require_once('..\App\FacebookSubscription.php');


class BroadcastMessage {

    public function getMessageId($text) {
        $broadcastMessage =  '{
        "messages":
            [{
                "dynamic_text": {
                    "text": "'. $text .' to you, {{first_name}}",
                    "fallback_text": "${text}"
                }
            }]
        }';

        $access_token = getenv('FACEBOOK_TOKEN');
        $api_url = 'https://graph.facebook.com/v2.11/me/message_creatives?access_token='.$access_token;
        $cSession = curl_init($api_url);
        curl_setopt($cSession, CURLOPT_POST, 1);
        curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cSession, CURLOPT_POSTFIELDS, $broadcastMessage);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $message_creative_id=curl_exec($cSession);
        curl_close($cSession);
        return $message_creative_id;
    }
}

class Broadcast {

    public function broadcastMessage(String $text){
        $message = new BroadcastMessage();
        $message_creative_id = $message->getMessageId($text);
        $message_creative_id= substr($message_creative_id, 0, -1);
        $message_creative_id .= ', "custom_label_id":2439019392790895}';
        $access_token = getenv('FACEBOOK_TOKEN');;
        $api_broadcast_url = 'https://graph.facebook.com/v2.11/me/broadcast_messages?access_token='.$access_token;
        $chBroadcast = curl_init($api_broadcast_url);
        curl_setopt($chBroadcast, CURLOPT_POST, 1);
        curl_setopt($chBroadcast, CURLOPT_POSTFIELDS, $message_creative_id);
        curl_setopt($chBroadcast, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($chBroadcast);
        curl_close($chBroadcast);
        return $result;
    }
}

$something = new FacebookSubscription();
$somethingnew = new Broadcast();
echo $something->retrieveLabel("1678248702231382");
