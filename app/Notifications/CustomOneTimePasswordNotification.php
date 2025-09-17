<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Spatie\OneTimePasswords\Models\OneTimePassword;
use Spatie\OneTimePasswords\Notifications\OneTimePasswordNotification;

final class CustomOneTimePasswordNotification extends OneTimePasswordNotification
{
    use Queueable;

    /**
     * @param array<int, string> $channels
     */
    public function __construct(
        OneTimePassword $oneTimePassword,
        private readonly array $channels = ['mail']
    ) {
        parent::__construct($oneTimePassword);
    }

    /** @return array<string> */
    public function via(object $notifiable): array
    {
        return array_map(
            static fn (string $channel): string => $channel === 'WhatsApp' ? WhatsAppChannel::class : $channel,
            $this->channels
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your verification code'))
            ->line(__('Use the code below to continue'))
            ->line($this->oneTimePassword->password)
            ->line(__('This code expires soon'));
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content(__('Your code is') . ' ' . $this->oneTimePassword->password);
    }

    /**
     * WhatsApp template payload for our custom channel.
     *
     * @return array{
     *   messaging_product: 'whatsapp',
     *   to: string,
     *   type: 'template',
     *   language: array{code: string},
     *   template: array{
     *     name: string,
     *     variables: list<string>,
     *     urlParameters?: list<string>,
     *     ttl?: int,
     *     language?: string
     *   }
     * }
     */
    public function toWhatsApp(object $notifiable): array
    {
        $to = method_exists($notifiable, 'routeNotificationForWhatsApp')
            ? (string) $notifiable->routeNotificationForWhatsApp($this)
            : (string) (data_get($notifiable, 'phone', ''));

        $code         = (string) $this->oneTimePassword->password;
        $supportPhone = (string) config('services.whatsapp.support_phone');

        return [
            'messaging_product' => 'whatsapp',
            'to'                => $to,
            'type'              => 'template',
            'language'          => ['code' => 'en_US'],
            'template'          => [
                'name'      => 'otp_code',
                'variables' => [$code, $supportPhone],
                'urlParameters' => [$code],
            ],
        ];
    }
}
