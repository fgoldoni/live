<?php

declare(strict_types=1);

use App\Services\Notifications\MetaCloudClient;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Facades\Http;

it('sends a text message through Meta Cloud API', function (): void {
    config()->set('services.whatsapp.api_url', 'https://graph.facebook.com/v20.0');
    config()->set('services.whatsapp.access_token', 'token_x');
    config()->set('services.whatsapp.phone_number_id', '12345');

    Http::fake(['*' => Http::response(['messages' => []], 200)]);

    $client = new MetaCloudClient(app(HttpFactory::class));
    $ok     = $client->sendText('+49 176 4715 9315', 'Hello');

    expect($ok)->toBeTrue();

    Http::assertSent(function ($request): bool {
        $urlOk = str_ends_with((string) $request->url(), '/12345/messages');
        $json  = $request->data();

        return $urlOk
            && $json['messaging_product'] === 'whatsapp'
            && $json['type'] === 'text'
            && $json['text']['body'] === 'Hello'
            && $json['to'] === '4917647159315';
    });
});

it('sends a template with variables and url params', function (): void {
    config()->set('services.whatsapp.api_url', 'https://graph.facebook.com/v20.0');
    config()->set('services.whatsapp.access_token', 'token_x');
    config()->set('services.whatsapp.phone_number_id', '12345');

    Http::fake(['*' => Http::response(['messages' => []], 200)]);

    $client = new MetaCloudClient(app(HttpFactory::class));
    $ok     = $client->sendTemplate('+1 (202) 555-0199', 'otp_code', ['123456', '+49 40 123'], ['https://x.y/abc'], 300, 'de_DE');

    expect($ok)->toBeTrue();

    Http::assertSent(function ($request): bool {
        $json       = $request->data();
        $components = $json['template']['components'];
        $body       = collect($components)->firstWhere('type', 'body');
        $button     = collect($components)->firstWhere('type', 'button');

        return $json['type'] === 'template'
            && $json['to'] === '12025550199'
            && $json['message_send_ttl_seconds'] === 900 // valeur codée dans le client
            && $json['template']['name'] === 'otp_code'
            && $json['template']['language']['code'] === 'de_DE'
            && $body !== null
            && $button !== null
            && $body['parameters'][0]['text'] === '123456'
            && $body['parameters'][1]['text'] === '+49 40 123'
            && $button['sub_type'] === 'url'
            && $button['parameters'][0]['text'] === 'https://x.y/abc';
    });
});
