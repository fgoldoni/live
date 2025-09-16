<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Contracts\Notifications\WhatsAppClient;
use Illuminate\Notifications\Notification;

/**
 * @phpstan-type WhatsAppTemplate array{
 *   name: string,
 *   vars?: list<string>,
 *   urlParams?: list<string>,
 *   ttl?: int,
 *   language?: string
 * }
 * @phpstan-type WhatsAppPayload array{
 *   to?: string,
 *   text?: string,
 *   template?: WhatsAppTemplate
 * }
 */
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



        /** @var WhatsAppPayload $data */
        $data = (array) $notification->toWhatsApp($notifiable);
        $to   = (string) ($data['to'] ?? ($notifiable->routeNotificationForWhatsApp() ?? ''));

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
                $t['ttl'] ?? null,
                (string) ($t['language'] ?? 'en_US')
            );

            return;
        }

        $text = (string) ($data['text'] ?? '');

        if ($text !== '') {
            $this->whatsAppClient->sendText($to, $text);
        }
    }
}
