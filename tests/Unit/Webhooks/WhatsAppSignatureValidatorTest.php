<?php

declare(strict_types=1);

use App\Jobs\ProcessWhatsAppWebhook;
use App\Webhooks\WhatsAppSignatureValidator;
use Illuminate\Http\Request;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile;
use Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo;

it(/**
 * @throws \Spatie\WebhookClient\Exceptions\InvalidConfig
 */ 'validates correct X-Hub-Signature-256 header', function (): void {
    $secret   = 'super-secret';
    $content  = '{"a":1}';
    $expected = 'sha256=' . hash_hmac('sha256', $content, $secret);

    $request = Request::create('/webhook', 'POST', server: [
        'HTTP_X_HUB_SIGNATURE_256' => $expected,
    ], content: $content);

    $config = new WebhookConfig([
        'name'                  => 'whatsapp',
        'signing_secret'        => $secret,
        'signature_header_name' => 'X-Hub-Signature-256',
        'signature_validator'   => WhatsAppSignatureValidator::class,
        'webhook_profile'       => ProcessEverythingWebhookProfile::class,
        'webhook_response'      => DefaultRespondsTo::class,
        'webhook_model'         => WebhookCall::class,
        'store_headers'         => [],
        'process_webhook_job'   => ProcessWhatsAppWebhook::class,
    ]);

    $validator = new WhatsAppSignatureValidator;

    expect($validator->isValid($request, $config))->toBeTrue();
});

it('fails when header missing or invalid', function (): void {
    $secret  = 'super-secret';
    $content = '{"a":1}';

    $request = Request::create('/webhook', 'POST', content: $content);

    $config = new WebhookConfig([
        'name'                  => 'whatsapp',
        'signing_secret'        => $secret,
        'signature_header_name' => 'X-Hub-Signature-256',
        'signature_validator'   => WhatsAppSignatureValidator::class,
        'webhook_profile'       => ProcessEverythingWebhookProfile::class,
        'webhook_response'      => DefaultRespondsTo::class,
        'webhook_model'         => WebhookCall::class,
        'store_headers'         => [],
        'process_webhook_job'   => ProcessWhatsAppWebhook::class,
    ]);

    $validator = new WhatsAppSignatureValidator;

    expect($validator->isValid($request, $config))->toBeFalse();
});
