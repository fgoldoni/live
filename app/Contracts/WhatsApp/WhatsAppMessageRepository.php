<?php

// app/Contracts/WhatsApp/WhatsAppMessageRepository.php
declare(strict_types=1);

namespace App\Contracts\WhatsApp;

use App\DTO\WhatsApp\MessageStatusDto;
use App\Models\WhatsAppMessage;

interface WhatsAppMessageRepository
{
    public function upsertStatus(MessageStatusDto $messageStatusDto): WhatsAppMessage;

    public function prune(?int $keepLast = null, ?int $olderThanDays = null): int;
}
