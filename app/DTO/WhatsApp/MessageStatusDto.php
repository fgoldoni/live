<?php

// app/DTO/WhatsApp/MessageStatusDto.php
declare(strict_types=1);

namespace App\DTO\WhatsApp;

use App\Enums\WhatsAppStatus;
use Carbon\CarbonImmutable;

final readonly class MessageStatusDto
{
    public function __construct(
        public string $wamid,
        public string $recipientId,
        public WhatsAppStatus $status,
        public ?CarbonImmutable $occurredAt,
        public ?string $conversationId,
        public ?string $conversationOrigin,
        public ?string $category,
        public ?bool $billable,
        public ?string $pricingModel,
        public ?string $phoneNumberId,
        public ?string $displayPhoneNumber,
        public array $rawPayload
    ) {
    }
}
