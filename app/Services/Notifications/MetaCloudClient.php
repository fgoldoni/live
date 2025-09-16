<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Contracts\Notifications\WhatsAppClient;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\Response;
use InvalidArgumentException;

/**
 * @phpstan-type WaPayload array<string, mixed>
 */
final class MetaCloudClient implements WhatsAppClient
{
    public function __construct(
        private HttpFactory $http,
        private string $apiUrl = '',
        private string $accessToken = '',
        private string $phoneNumberId = ''
    ) {
        $this->apiUrl        = $this->apiUrl        ?: (string) config('services.whatsapp.api_url', '');
        $this->accessToken   = $this->accessToken   ?: (string) config('services.whatsapp.access_token', '');
        $this->phoneNumberId = $this->phoneNumberId ?: (string) config('services.whatsapp.phone_number_id', '');

        if ($this->apiUrl === '' || $this->accessToken === '' || $this->phoneNumberId === '') {
            throw new InvalidArgumentException('WhatsApp Cloud API configuration is missing.');
        }
    }

    public function sendText(string $to, string $text): bool
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to'   => $this->normalizePhone($to),
            'type' => 'text',
            'text' => ['body' => $text],
        ];

        return $this->sendRequest($payload)->successful();
    }

    /**
     * @param list<string> $vars
     * @param list<string> $urlParams
     */
    public function sendTemplate(
        string $to,
        string $templateName,
        array $vars = [],
        array $urlParams = [],
        ?int $ttlSeconds = null,
        string $language = 'fr'
    ): bool {
        $components = [];

        if ($vars !== []) {
            $components[] = [
                'type'       => 'body',
                'parameters' => array_map(
                    static fn (string $v) => ['type' => 'text', 'text' => $v],
                    array_values($vars)
                ),
            ];
        }

        if ($urlParams !== []) {
            foreach (array_values($urlParams) as $index => $param) {
                $components[] = [
                    'type'       => 'button',
                    'sub_type'   => 'URL',
                    'index'      => $index,
                    'parameters' => [['type' => 'text', 'text' => $param]],
                ];
            }
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'       => $this->normalizePhone($to),
            'type'     => 'template',
            'template' => [
                'name'       => $templateName,
                'language'   => ['code' => $language],
                'components' => $components,
            ],
        ];

        if ($ttlSeconds !== null) {
            $payload['message_send_ttl_seconds'] = $ttlSeconds;
        }

        return $this->sendRequest($payload)->successful();
    }

    /** @param WaPayload $payload */
    private function sendRequest(array $payload): Response
    {
        $endpoint = rtrim($this->apiUrl, '/') . '/' . $this->phoneNumberId . '/messages';

        return $this->http
            ->withToken($this->accessToken)
            ->acceptJson()
            ->asJson()
            ->post($endpoint, $payload);
    }

    private function normalizePhone(string $number): string
    {
        return preg_replace('/\D+/', '', $number) ?? $number;
    }
}
