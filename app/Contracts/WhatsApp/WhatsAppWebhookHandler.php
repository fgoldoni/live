<?php

declare(strict_types=1);

namespace App\Contracts\WhatsApp;

interface WhatsAppWebhookHandler
{
    /**
     * @param array<string,mixed> $payload
     */
    public function handle(array $payload): void;
}
