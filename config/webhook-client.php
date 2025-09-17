<?php

return [
    'configs' => [
        [
            'name'                  => 'whatsapp',
            'signing_secret'        => env('FACEBOOK_APP_SECRET'),
            'signature_header_name' => 'X-Hub-Signature-256',
            'signature_validator'   => \App\Webhooks\WhatsAppSignatureValidator::class,
            'process_webhook_job'   => \App\Jobs\ProcessWhatsAppWebhook::class,
            'webhook_profile'       => \Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile::class,
            'webhook_response'      => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_model'         => \Spatie\WebhookClient\Models\WebhookCall::class,
        ],
    ],

    'delete_after_days' => 30,
    'add_unique_token_to_route_name' => false,
];
