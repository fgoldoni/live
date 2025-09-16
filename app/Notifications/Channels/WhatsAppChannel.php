<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Contracts\Notifications\WhatsAppClient;
use Illuminate\Notifications\Notification;

final readonly class WhatsAppChannel
{
    public function __construct(private WhatsAppClient $whatsAppClient)
    {
    }

    public function send(mixed $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $data = (array) $notification->toWhatsApp($notifiable);
        $to   = $data['to'] ?? $notifiable->routeNotificationForWhatsApp() ?? '';

        if ($to === '') {
            return;
        }

        if (isset($data['template']) && is_array($data['template'])) {
            $t    = $data['template'];
            $name = (string) ($t['name'] ?? '');

            if ($name === '') {
                return;
            }

            $this->whatsAppClient->sendTemplate(
                $to,
                $name,
                (array) ($t['vars'] ?? []),
                (array) ($t['urlParams'] ?? []),
                isset($t['ttl']) ? (int) $t['ttl'] : null,
                (string) ($t['language'] ?? 'fr')
            );

            return;
        }

        $text = (string) ($data['text'] ?? '');

        if ($text === '') {
            return;
        }

        $this->whatsAppClient->sendText($to, $text);
    }
}
