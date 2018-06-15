<?php

namespace App\Conversations;

class BroadcastMessage
{

    public function getMessageId($text)
    {
        $broadcastMessage = '{
        "messages":
            [{
                "dynamic_text": {
                    "text": "' . $text . ' to you, {{first_name}}",
                    "fallback_text": "${text}"
                }
            }]
        }';

        $access_token = getenv('FACEBOOK_TOKEN');
        $api_url = 'https://graph.facebook.com/v2.11/me/message_creatives?access_token=' . $access_token;
        $cSession = curl_init($api_url);
        curl_setopt($cSession, CURLOPT_POST, 1);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_POSTFIELDS, $broadcastMessage);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $message_creative_id = curl_exec($cSession);
        curl_close($cSession);
        return $message_creative_id;
    }
}
