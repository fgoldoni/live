<?php

return [
    'api_url' => env('VONAGE_API_URL', 'https://api.nexmo.com/v1/messages'),
    'api_key' => env('VONAGE_API_KEY', env('VONAGE_KEY', '')),
    'api_secret' => env('VONAGE_API_SECRET', env('VONAGE_SECRET', '')),
    'signature_secret' => env('VONAGE_SIGNATURE_SECRET', ''),
    'signature_method' => env('VONAGE_SIGNATURE_METHOD', ''),
    'sms_from' => env('VONAGE_SMS_FROM', ''),
    'whatsapp_from' => env('VONAGE_WHATSAPP_FROM', ''),
    'application_id' => env('VONAGE_APPLICATION_ID', ''),
    'private_key_path' => is_string(env('VONAGE_PRIVATE_KEY_PATH'))
        ? base_path(env('VONAGE_PRIVATE_KEY_PATH'))
        : base_path('storage/app/keys/vonage_private.key'),
    'namespace' => env('VONAGE_WHATSAPP_NAMESPACE', '3e52cd3b_91fd_4889_b111_8474e4b30acd'),
];
