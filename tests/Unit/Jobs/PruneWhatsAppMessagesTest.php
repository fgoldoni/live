<?php

declare(strict_types=1);

use App\Actions\WhatsApp\PruneMessages;
use App\Jobs\PruneWhatsAppMessages;
use Mockery as m;

it('uses provided args when set', function (): void {
    $mock = m::mock(PruneMessages::class);
    $mock->shouldReceive('execute')->once()->with(50, 7);
    $job = new PruneWhatsAppMessages(keepLast: 50, olderThanDays: 7);
    $job->handle($mock);
});

it('falls back to config when args are null', function (): void {
    config()->set('services.whatsapp.retention.keep_last', 100000);
    config()->set('services.whatsapp.retention.days', 30);

    $mock = m::mock(PruneMessages::class);
    $mock->shouldReceive('execute')->once()->with(100000, 30);

    $job = new PruneWhatsAppMessages;
    $job->handle($mock);
});
