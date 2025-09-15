<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Spatie\OneTimePasswords\Models\OneTimePassword;
use Spatie\OneTimePasswords\Notifications\OneTimePasswordNotification;

final class CustomOneTimePasswordNotification extends OneTimePasswordNotification implements ShouldQueue
{
    use Queueable;
    /**
     * @param array<int,string> $channels
     */
    public function __construct(
        OneTimePassword $oneTimePassword,
        private readonly array $channels = ['mail']
    ) {
        parent::__construct($oneTimePassword);
    }

    /**
     * @return array<string>
     */
    public function via(object $notifiable): array
    {
        return array_map(fn (string $c): string => $c === 'whatsapp' ? WhatsAppChannel::class : $c, $this->channels);
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
        return (new VonageMessage)->content(__('Your code is') . ' ' . $this->oneTimePassword->password);
    }

    /**
     * @return array{to:string,text:string}
     */
    public function toWhatsapp(object $notifiable): array
    {
        $to = method_exists($notifiable, 'routeNotificationForWhatsapp') ? (string) $notifiable->routeNotificationForWhatsapp($this) : (string) ($notifiable->phone ?? '');

        return ['to' => $to, 'text' => __('Your code is') . ' ' . $this->oneTimePassword->password];
    }
}
