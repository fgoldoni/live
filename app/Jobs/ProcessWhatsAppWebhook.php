<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessWhatsAppWebhook implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(public WebhookCall $webhookCall) {}

    public function handle(): void
    {
        $payload = $this->webhookCall->payload;

        dd($payload);
    }
}
