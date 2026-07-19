<?php

return [

    'queue' => [

        'enabled' => env('NOTIFICATION_QUEUE_ENABLED', false),

        'connection' => env('NOTIFICATION_QUEUE_CONNECTION', null),

        'name' => env('NOTIFICATION_QUEUE_NAME', 'default'),
    ],

    'sms' => [

        'api_key' => env('SMS_API_KEY'),

        'url' => env('SMS_URL', 'https://api.sms.ir/v1/send/verify'),

        'template_id' => env('SMS_TEMPLATE_ID'),
    ],
];