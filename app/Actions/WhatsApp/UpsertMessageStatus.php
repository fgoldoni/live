<?php

// app/Actions/WhatsApp/UpsertMessageStatus.php
declare(strict_types=1);

namespace App\Actions\WhatsApp;

use App\Contracts\WhatsApp\WhatsAppMessageRepository;
use App\DTO\WhatsApp\MessageStatusDto;
use App\Events\WhatsApp\MessageStatusChanged;
use App\Models\WhatsAppMessage;
use Illuminate\Contracts\Events\Dispatcher;

final readonly class UpsertMessageStatus
{
    public function __construct(
        private WhatsAppMessageRepository $whatsAppMessageRepository,
        private Dispatcher $dispatcher
    ) {
    }

    public function execute(MessageStatusDto $messageStatusDto): void
    {
        $before = WhatsAppMessage::query()->where('wamid', $messageStatusDto->wamid)->value('status');
        $saved  = $this->whatsAppMessageRepository->upsertStatus($messageStatusDto);

        if ($before !== $saved->status) {
            $this->dispatcher->dispatch(new MessageStatusChanged($saved, $before, $saved->status));
        }
    }
}
