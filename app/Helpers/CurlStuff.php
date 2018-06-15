<?php
$broadcastMessage =  '{    
  "messages":[
    {
    "dynamic_text": {
      "text": "Hello , {{first_name}}! Do you like cats?",
      "fallback_text": "Hello friend, do you like cats?"
    } 
  }
  ]
}';

$access_token = env('FACEBOOK_TOKEN');
$post_fields = [
    'access_token' => env('FACEBOOK_TOKEN')
];
$api_url = 'https://graph.facebook.com/v2.11/me/message_creatives?access_token='.$access_token;
$cSession = curl_init($api_url);
curl_setopt($cSession, CURLOPT_POST, 1);
curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
curl_setopt($cSession, CURLOPT_POSTFIELDS, $broadcastMessage);
curl_setopt($cSession, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$message_creative_id=curl_exec($cSession);
curl_close($cSession);

$api_broadcast_url = 'https://graph.facebook.com/v2.11/me/broadcast_messages?access_token='.$access_token;

$creativeIdJSON ='{
    "message_creative_id": "' . $message_creative_id . '"
  }';
$chBroadcast = curl_init($api_broadcast_url);
curl_setopt($chBroadcast, CURLOPT_POST, 1);
curl_setopt($chBroadcast, CURLOPT_POSTFIELDS, $creativeIdJSON);
curl_setopt($chBroadcast, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$result = curl_exec($chBroadcast);
curl_close($chBroadcast);
//"attachment":{
//    "type":"template",
//                "payload":{
//        "template_type":"generic",
//                    "elements":[
//                        {
//                            "title":"An amazing cat picture!!",
//                            "image_url":"https://s3-us-west-1.amazonaws.com/foundanimals.org-wordpress/wp-content/uploads/2018/02/06164355/InstagramCats_twenty20_2e742bff-a0de-49c1-ae74-82b7dbdf716e-1024x683.jpg",
//                            "subtitle":"CAT",
//                            "buttons":[
//                                {
//                                    "type":"web_url",
//                                    "url":"http://www.cats.com/",
//                                    "title":"CATSS"
//                                },
//                                {
//                                    "type":"web_url",
//                                    "url":"https://www.bluecross.org.uk/rehome/dog",
//                                    "title":"fuck that, get me dog"
//                                }
//                            ]
//                        }
//                    ]
//                }
//            }
?>
