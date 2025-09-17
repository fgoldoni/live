<?php

// app/Services/WhatsApp/DefaultWebhookHandler.php
declare(strict_types=1);

namespace App\Services\WhatsApp;

use App\Actions\WhatsApp\UpsertMessageStatus;
use App\Contracts\WhatsApp\WhatsAppWebhookHandler;
use App\DTO\WhatsApp\MessageStatusDto;
use App\Enums\WhatsAppStatus;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;

final readonly class DefaultWebhookHandler implements WhatsAppWebhookHandler
{
    public function __construct(private UpsertMessageStatus $upsertMessageStatus)
    {
    }

    public function handle(array $payload): void
    {
        $changes = (array) Arr::get($payload, 'entry.0.changes', []);
        foreach ($changes as $change) {
            $value              = $change['value'] ?? [];
            $phoneNumberId      = (string) Arr::get($value, 'metadata.phone_number_id', '');
            $displayPhoneNumber = (string) Arr::get($value, 'metadata.display_phone_number', '');
            foreach ((array) ($value['statuses'] ?? []) as $status) {
                $statusStr = (string) Arr::get($status, 'status', 'accepted');
                $enum      = WhatsAppStatus::from(match (true) {
                    in_array($statusStr, array_column(WhatsAppStatus::cases(), 'value'), true) => $statusStr,
                    default                                                                    => 'accepted'
                });
                $timestamp = Arr::get($status, 'timestamp');
                $dto       = new MessageStatusDto(
                    wamid: (string) Arr::get($status, 'id', ''),
                    recipientId: (string) Arr::get($status, 'recipient_id', ''),
                    status: $enum,
                    occurredAt: $timestamp ? CarbonImmutable::createFromTimestamp((int) $timestamp) : null,
                    conversationId: Arr::get($status, 'conversation.id'),
                    conversationOrigin: Arr::get($status, 'conversation.origin.type'),
                    category: Arr::get($status, 'pricing.category'),
                    billable: Arr::get($status, 'pricing.billable'),
                    pricingModel: Arr::get($status, 'pricing.pricing_model'),
                    phoneNumberId: $phoneNumberId,
                    displayPhoneNumber: $displayPhoneNumber,
                    rawPayload: $payload
                );
                $this->upsertMessageStatus->execute($dto);
            }
        }
    }
}
