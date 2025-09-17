<?php

// app/Jobs/ProcessWhatsAppWebhook.php
declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\WhatsApp\WhatsAppWebhookHandler;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

final class ProcessWhatsAppWebhook extends SpatieProcessWebhookJob
{
    public function handle(): void
    {
        app(WhatsAppWebhookHandler::class)->handle($this->webhookCall->payload);
    }
}
