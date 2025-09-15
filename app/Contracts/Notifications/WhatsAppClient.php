<?php

declare(strict_types=1);

namespace App\Contracts\Notifications;

interface WhatsAppClient
{
    public function sendText(string $phoneE164, string $text): void;
}
