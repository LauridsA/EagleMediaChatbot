<?php

use App\Conversations\BroadcastMessage;

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
