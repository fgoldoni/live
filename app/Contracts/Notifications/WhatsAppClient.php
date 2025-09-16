<?php

declare(strict_types=1);

namespace App\Contracts\Notifications;

interface WhatsAppClient
{
    public function sendText(string $phoneE164, string $text): bool;

    public function sendTemplate(
        string $to,
        string $templateName,
        array $vars = [],
        array $urlParams = [],
        ?int $ttlSeconds = null,
        string $language = 'fr'
    ): bool;
}
