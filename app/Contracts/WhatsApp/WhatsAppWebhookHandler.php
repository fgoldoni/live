<?php

// app/Contracts/WhatsApp/WhatsAppWebhookHandler.php
declare(strict_types=1);

namespace App\Contracts\WhatsApp;

interface WhatsAppWebhookHandler
{
    public function handle(array $payload): void;
}
