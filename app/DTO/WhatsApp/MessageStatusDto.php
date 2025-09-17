<?php

// app/DTO/WhatsApp/MessageStatusDto.php
declare(strict_types=1);

namespace App\DTO\WhatsApp;

use App\Enums\WhatsAppStatus;
use Carbon\Carbon;

final readonly class MessageStatusDto
{
    /**
     * @param array<string,mixed> $rawPayload
     */
    public function __construct(
        public string $wamid,
        public string $recipientId,
        public WhatsAppStatus $status,
        public ?Carbon $occurredAt,
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
