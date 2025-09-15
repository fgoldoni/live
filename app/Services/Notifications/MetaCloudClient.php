<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Contracts\Notifications\WhatsAppClient;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Str;
use RuntimeException;

final readonly class MetaCloudClient implements WhatsAppClient
{
    public function __construct(private HttpFactory $httpFactory)
    {
    }

    public function sendText(string $phoneE164, string $text): void
    {
        $token   = (string) config('services.meta_wa.token');
        $phoneId = (string) config('services.meta_wa.phone_number_id');

        if ($token === '' || $phoneId === '') {
            throw new RuntimeException('WhatsApp is not configured');
        }

        $endpoint = 'https://graph.facebook.com/v20.0/' . $phoneId . '/messages';
        $payload  = [
            'messaging_product' => 'whatsapp',
            'to'                => $phoneE164,
            'type'              => 'text',
            'text'              => ['body' => Str::limit($text, 1000, '')],
        ];
        $this->httpFactory->withToken($token)->post($endpoint, $payload)->throw();
    }
}
