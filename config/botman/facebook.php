<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Facebook Token
    |--------------------------------------------------------------------------
    |
    | Your Facebook application you received after creating
    | the messenger page / application on Facebook.
    |
    */
    'token' => env('FACEBOOK_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Facebook App Secret
    |--------------------------------------------------------------------------
    |
    | Your Facebook application secret, which is used to verify
    | incoming requests from Facebook.
    |
    */
    'app_secret' => env('FACEBOOK_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Facebook Verification
    |--------------------------------------------------------------------------
    |
    | Your Facebook verification token, used to validate the webhooks.
    |
    */
    'verification' => env('FACEBOOK_VERIFICATION'),

    /*
    |--------------------------------------------------------------------------
    | Facebook Start Button Payload
    |--------------------------------------------------------------------------
    |
    | The payload which is sent when the Get Started Button is clicked.
    |
    */
    'start_button_payload' => 'YOUR_PAYLOAD_TEXT',


    /*
    |--------------------------------------------------------------------------
    | Facebook Greeting Text
    |--------------------------------------------------------------------------
    |
    | Your Facebook Greeting Text which will be shown on your message start screen.
    |
    */
    'greeting_text' => [
        'greeting' => [
            [
                'locale' => 'default',
                'text' => 'Jeg er en bot! Og Kasper laver webshops!',
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Facebook Persistent Menu
    |--------------------------------------------------------------------------
    |
    | Example items for your persistent Facebook menu.
    |
    */

    'persistent_menu' => [
        [
            'locale' => 'default',
            'composer_input_disabled' => 'false',
            'call_to_actions' => [
                [
                    'title' => 'Min Profil',
                    'type' => 'nested',
                    'call_to_actions' => [
                        [
                            'title' => 'Tilmeld Nyhedsbrev (email)',
                            'type' => 'postback',
                            'payload' => 'EMAIL_PAYLOAD',
                        ],
                        [
                            'title' => 'Afmeld Nyhedsbrev (email)',
                            'type' => 'postback',
                            'payload' => 'EMAIL_PAYLOAD',
                        ],
                        [
                            'title' => 'Tilmed opdateringer',
                            'type' => 'postback',
                            'payload' => 'UPDATES_PAYLOAD',
                        ],
                        [
                            'title' => 'Afmeld opdateringer',
                            'type' => 'postback',
                            'payload' => 'UPDATES_PAYLOAD',
                        ],
                    ],
                ],
                [
                    'type' => 'web_url',
                    'title' => 'Indstillinger',
                    'url' => 'http://botman.io',
                    'webview_height_ratio' => 'full',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Facebook Domain Whitelist
    |--------------------------------------------------------------------------
    |
    | In order to use domains you need to whitelist them
    |
    */
    'whitelisted_domains' => [
        'https://eaglemedia.dk',
    ],
];
