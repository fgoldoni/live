<?php

// app/Actions/WhatsApp/PruneMessages.php
declare(strict_types=1);

namespace App\Actions\WhatsApp;

use App\Contracts\WhatsApp\WhatsAppMessageRepository;

final readonly class PruneMessages
{
    public function __construct(private WhatsAppMessageRepository $whatsAppMessageRepository)
    {
    }

    public function execute(?int $keepLast = null, ?int $olderThanDays = null): int
    {
        return $this->whatsAppMessageRepository->prune($keepLast, $olderThanDays);
    }
}
