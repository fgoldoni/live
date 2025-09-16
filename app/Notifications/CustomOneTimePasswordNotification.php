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
            static fn (string $c): string => $c === 'WhatsApp' ? WhatsAppChannel::class : $c,
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
     *   messaging_product:'whatsapp',
     *   to: string,
     *   type:'template',
     *   template: array{
     *     name: string,
     *     vars: array{0:string,1:string,2:int},
     *     urlParams: array<int,string>
     *   }
     * }
     */
    public function toWhatsapp(object $notifiable): array
    {
        $to = method_exists($notifiable, 'routeNotificationForWhatsapp')
            ? (string) $notifiable->routeNotificationForWhatsapp($this)
            : (string) (data_get($notifiable, 'phone', ''));

        $name = (string) data_get($notifiable, 'name', 'User');
        $code = (string) $this->oneTimePassword->password;
        $ttl  = (int) config('one-time-passwords.default_expires_in_minutes', 10);

        return [
            'messaging_product' => 'whatsapp',
            'to'                => $to,
            'type'              => 'template',
            'template'          => [
                'name'      => 'account_otp_verification_2',
                'vars'      => [$name, $code, $ttl],
                'urlParams' => [$code],
            ],
        ];
    }
}
