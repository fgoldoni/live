<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Contracts\Notifications\SmsSender;
use Illuminate\Http\Client\Factory as HttpFactory;
use RuntimeException;

final readonly class VonageSmsSender implements SmsSender
{
    public function __construct(private HttpFactory $httpFactory)
    {
    }

    public function send(string $to, string $text): void
    {
        $key    = (string) config('services.vonage.key');
        $secret = (string) config('services.vonage.secret');
        $from   = (string) config('services.vonage.sms_from');

        if ($key === '' || $secret === '' || $from === '') {
            throw new RuntimeException('Vonage is not configured');
        }

        $endpoint = 'https://rest.nexmo.com/sms/json';
        $payload  = [
            'api_key'    => $key,
            'api_secret' => $secret,
            'to'         => $to,
            'from'       => $from,
            'text'       => $text,
            'type'       => 'text',
        ];
        $this->httpFactory->asForm()->post($endpoint, $payload)->throw();
    }
}
