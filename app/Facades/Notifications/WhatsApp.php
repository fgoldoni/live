<?php

declare(strict_types=1);

namespace App\Facades\Notifications;

use App\Contracts\Notifications\WhatsAppClient;
use Illuminate\Support\Facades\Facade;

final class WhatsApp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return WhatsAppClient::class;
    }
}
