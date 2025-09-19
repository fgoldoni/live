<?php

declare(strict_types=1);

use App\Contracts\Notifications\WhatsAppClient;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as m;

class WhatsAppNotifiable
{
    use Notifiable;

    public string $phone = '+4917647159315';

    public function routeNotificationForWhatsApp(): string
    {
        return $this->phone;
    }
}

class TemplateNotification extends Notification
{
    public function via($notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp(object $notifiable): array
    {
        return [
            'template' => [
                'name'          => 'otp_code',
                'variables'     => ['123456'],
                'urlParameters' => ['https://foo'],
                'language'      => 'en_US',
            ],
        ];
    }
}

class TextNotification extends Notification
{
    public function via($notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp(object $notifiable): array
    {
        return [
            'text' => 'Hi there',
        ];
    }
}

it('dispatches template via client', function (): void {
    $mock = m::mock(WhatsAppClient::class);
    $mock->shouldReceive('sendTemplate')
        ->once()
        ->withArgs(fn (string $to, string $name, array $vars, array $urls, ?int $ttl, string $lang): bool => $to === '+4917647159315'
            && $name === 'otp_code'
            && $vars === ['123456']
            && $urls === ['https://foo']
            && $ttl === null
            && $lang === 'en_US')
        ->andReturnTrue();

    $channel = new WhatsAppChannel($mock);
    $channel->send(new WhatsAppNotifiable, new TemplateNotification);
});

it('dispatches text via client', function (): void {
    $mock = m::mock(WhatsAppClient::class);
    $mock->shouldReceive('sendText')
        ->once()
        ->with('+4917647159315', 'Hi there')
        ->andReturnTrue();

    $channel = new WhatsAppChannel($mock);
    $channel->send(new WhatsAppNotifiable, new TextNotification);
});

it('does nothing when no route + no to provided', function (): void {
    $mock = m::mock(WhatsAppClient::class);
    $mock->shouldNotReceive('sendText', 'sendTemplate');

    $channel = new WhatsAppChannel($mock);
    $anon    = new class {
        use Notifiable;
    };

    $channel->send($anon, new TextNotification);
});
