<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use App\Contracts\Notifications\WhatsAppClient;
use Illuminate\Notifications\Notification;

/**
 * @phpstan-type WhatsAppTemplate array{
 *   name: string,
 *   variables?: list<string>,
 *   urlParameters?: list<string>,
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
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        /** @var WhatsAppPayload $payload */
        $payload = (array) $notification->toWhatsApp($notifiable);

        $to = (string) ($payload['to'] ?? ($notifiable->routeNotificationForWhatsApp() ?? ''));

        if ($to === '') {
            return;
        }

        if (isset($payload['template']) && is_array($payload['template'])) {
            $template     = $payload['template'];
            $templateName = (string) ($template['name'] ?? '');

            if ($templateName === '') {
                return;
            }

            $languageCode = (string) ($template['language'] ?? 'en_US');

            $this->whatsAppClient->sendTemplate(
                $to,
                $templateName,
                (array) ($template['variables'] ?? []),
                (array) ($template['urlParameters'] ?? []),
                $template['ttl'] ?? null,
                $languageCode
            );

            return;
        }

        $text = (string) ($payload['text'] ?? '');

        if ($text !== '') {
            $this->whatsAppClient->sendText($to, $text);
        }
    }
}
