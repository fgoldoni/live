<?php

declare(strict_types=1);

use App\Actions\WhatsApp\UpsertMessageStatus;
use App\DTO\WhatsApp\MessageStatusDto;
use App\Enums\WhatsAppStatus;
use App\Events\WhatsApp\MessageStatusChanged;
use App\Models\WhatsAppMessage;
use App\Repositories\EloquentWhatsAppMessageRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('dispatches MessageStatusChanged when status actually changes', function (): void {
    WhatsAppMessage::query()->create([
        'wamid'       => 'wamid.1',
        'recipientId' => '4917',
        'status'      => WhatsAppStatus::ACCEPTED->value,
    ]);

    Event::fake([MessageStatusChanged::class]);

    $repo   = new EloquentWhatsAppMessageRepository(app());
    $action = new UpsertMessageStatus($repo, app(Dispatcher::class));

    $dto = new MessageStatusDto(
        wamid: 'wamid.1',
        recipientId: '4917',
        status: WhatsAppStatus::DELIVERED,
        occurredAt: now(),
        conversationId: 'c1',
        conversationOrigin: 'user_initiated',
        category: 'utility',
        billable: true,
        pricingModel: 'CBP',
        phoneNumberId: '123',
        displayPhoneNumber: '+1 202',
        rawPayload: ['x' => 1]
    );

    $action->execute($dto);

    Event::assertDispatched(MessageStatusChanged::class, fn (MessageStatusChanged $messageStatusChanged): bool => $messageStatusChanged->oldStatus === 'accepted' && $messageStatusChanged->newStatus === 'delivered' && $messageStatusChanged->message->wamid === 'wamid.1');

    expect(WhatsAppMessage::where('wamid', 'wamid.1')->value('deliveredAt'))->not->toBeNull();
});

it('does not dispatch when status remains the same', function (): void {
    WhatsAppMessage::query()->create([
        'wamid'       => 'wamid.2',
        'recipientId' => '4917',
        'status'      => WhatsAppStatus::SENT->value,
    ]);

    Event::fake([MessageStatusChanged::class]);

    $repo   = new EloquentWhatsAppMessageRepository(app());
    $action = new UpsertMessageStatus($repo, app(Dispatcher::class));

    $dto = new MessageStatusDto(
        wamid: 'wamid.2',
        recipientId: '4917',
        status: WhatsAppStatus::SENT,
        occurredAt: now(),
        conversationId: null,
        conversationOrigin: null,
        category: null,
        billable: null,
        pricingModel: null,
        phoneNumberId: null,
        displayPhoneNumber: null,
        rawPayload: []
    );

    $action->execute($dto);

    Event::assertNotDispatched(MessageStatusChanged::class);
});
