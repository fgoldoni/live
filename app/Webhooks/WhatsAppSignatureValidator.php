<?php

declare(strict_types=1);

namespace App\Webhooks;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

final class WhatsAppSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $received = (string) $request->header('X-Hub-Signature-256', '');

        if ($received === '' || ! str_starts_with($received, 'sha256=')) {
            return false;
        }

        $secret = $config->signingSecret ?? '';

        if ($secret === '') {
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

        return hash_equals($expected, $received);
    }
}
