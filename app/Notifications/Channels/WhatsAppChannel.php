<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Contracts\Notifications\WhatsAppClient;

final readonly class WhatsAppChannel
{
    public function __construct(private WhatsAppClient $whatsAppClient)
    {
    }

    public function send(object $notifiable, object $notification): void
    {
        if (!method_exists($notification, 'toWhatsapp')) {
            return;
        }

        $data = $notification->toWhatsapp($notifiable);

        if (!is_array($data) || !isset($data['to'], $data['text'])) {
            return;
        }

        $this->whatsAppClient->sendText((string) $data['to'], (string) $data['text']);
    }
}
