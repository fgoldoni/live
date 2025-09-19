<?php

declare(strict_types=1);

use App\DTO\WhatsApp\MessageStatusDto;
use App\Enums\WhatsAppStatus;
use App\Models\WhatsAppMessage;
use App\Repositories\EloquentWhatsAppMessageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('upserts and fills timestamps correctly', function (): void {
    $repo = new EloquentWhatsAppMessageRepository(app());

    $dto = new MessageStatusDto(
        wamid: 'wamid.AA',
        recipientId: '4917',
        status: WhatsAppStatus::READ,
        occurredAt: now(),
        conversationId: 'conv',
        conversationOrigin: 'user_initiated',
        category: 'utility',
        billable: true,
        pricingModel: 'CBP',
        phoneNumberId: 'pnid',
        displayPhoneNumber: '+1202',
        rawPayload: ['k' => 'v']
    );

    $whatsAppMessage = $repo->upsertStatus($dto);

    expect($whatsAppMessage->exists)->toBeTrue()
        ->and($whatsAppMessage->readAt)->not->toBeNull()
        ->and($whatsAppMessage->status)->toBe('read')
        ->and($whatsAppMessage->raw)->toBe(['k' => 'v']);
});

it('prunes by date and keeps last N', function (): void {
    WhatsAppMessage::factory()->count(5)->create(['created_at' => now()->subDays(40)]);
    WhatsAppMessage::factory()->count(7)->create(['created_at' => now()->subDays(10)]);

    $repo = new EloquentWhatsAppMessageRepository(app());

    $deleted = $repo->prune(keepLast: 6, olderThanDays: 30);

    expect($deleted)->toBeGreaterThan(0);
    expect(WhatsAppMessage::count())->toBe(6);
});
