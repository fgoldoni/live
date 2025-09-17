<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

final class WhatsAppWebhookController
{
    public function __invoke(Request $request)
    {
        $mode      = $request->query('hub_mode', $request->query('hub.mode'));
        $verify    = $request->query('hub_verify_token', $request->query('hub.verify_token'));
        $challenge = $request->query('hub_challenge', $request->query('hub.challenge'));

        if ($mode === 'subscribe' && hash_equals((string) config('services.whatsapp.webhook_verify_token'), (string) $verify)) {
            return response((string) $challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403);
    }
}
