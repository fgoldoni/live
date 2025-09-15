<?php

return [
    'api_key' => env('VONAGE_API_KEY', env('VONAGE_KEY', '')),
    'api_secret' => env('VONAGE_API_SECRET', env('VONAGE_SECRET', '')),
    'signature_secret' => env('VONAGE_SIGNATURE_SECRET', ''),
    'signature_method' => env('VONAGE_SIGNATURE_METHOD', ''),
    'sms_from' => env('VONAGE_SMS_FROM', ''),
    'application_id' => env('VONAGE_APPLICATION_ID', ''),
    'private_key' => env('VONAGE_PRIVATE_KEY', ''),
];
