<?php
// TODO create campaign
$cSession = curl_init();
curl_setopt($cSession,CURLOPT_URL,"https://graph.facebook.com/v2.11/act_<AD_ACCOUNT_ID>/campaigns");
curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
$result=curl_exec($cSession);
curl_close($cSession);
echo $result;
-F 'name=My campaign'
-F 'objective=MESSAGES'
-F 'status=PAUSED'
-F 'access_token'=env('FACEBOOK_VERIFICATION');
    https://graph.facebook.com/v2.11/act_<AD_ACCOUNT_ID>/campaigns
// TODO create add set
curl \
-F 'name=Sponsored Messages Ad Set' \
-F 'optimization_goal=IMPRESSIONS' \
-F 'billing_event=IMPRESSIONS' \
-F 'bid_amount=2500' \
-F 'daily_budget=10000' \
-F 'campaign_id=<CAMPAIGN_ID>' \
-F 'targeting=
 {"publisher_platforms":["messenger"],
  "messenger_positions":["sponsored_messages"],
  "device_platforms":["mobile", "desktop"]}' \
-F 'status=PAUSED' \
-F 'promoted_object={"page_id":<PAGE_ID>}' \
-F 'access_token=<ACCESS_TOKEN>' \
https://graph.facebook.com/<v2.11/act_<AD_ACCOUNT_ID>/adsets

// TODO ad creative
curl \
-F 'object_id=<PAGE_ID>' \
-F 'object_type=SHARE' \
-F 'messenger_sponsored_message={"message":{"text":"Sample Text", "quick_replies":[{"title":"Quick Reply Text", "content_type":"text"}]}}' \
-F 'access_token=<ACCESS_TOKEN>' \
https://graph.facebook.com/v2.11/act_<AD_ACCOUNT_ID>/adcreatives

// TODO finally; ad
curl \
-F 'name=My Ad' \
-F 'adset_id=<AD_SET_ID>' \
-F 'creative={"creative_id":"<CREATIVE_ID>"}' \
-F 'status=PAUSED' \
-F 'access_token=<ACCESS_TOKEN>' \
https://graph.facebook.com/v2.11/act_<AD_ACCOUNT_ID>/ads

curl -X POST -H "Content-Type: application/json" -d '{
  "messages":[
    <MESSAGE_OBJECT>
  ]
}' "https://graph.facebook.com/v2.11/me/message_creatives?access_token=<PAGE_ACCESS_TOKEN>"
