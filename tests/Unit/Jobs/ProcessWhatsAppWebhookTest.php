<?php

declare(strict_types=1);

use App\Contracts\WhatsApp\WhatsAppWebhookHandler;
use App\Jobs\ProcessWhatsAppWebhook;
use Mockery as m;
use Spatie\WebhookClient\Models\WebhookCall;

it('delegates payload to WhatsAppWebhookHandler', function (): void {
    $mock = m::mock(WhatsAppWebhookHandler::class);
    $mock->shouldReceive('handle')->once()->with(['foo' => 'bar']);
    app()->instance(WhatsAppWebhookHandler::class, $mock);

    $call = WebhookCall::make(['payload' => ['foo' => 'bar']]);
    $job  = new ProcessWhatsAppWebhook($call);
    $job->handle();
});
