<?php

declare(strict_types=1);

namespace App\Facades\Notifications;

use App\Contracts\Notifications\SmsSender;
use Illuminate\Support\Facades\Facade;

final class Sms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SmsSender::class;
    }
}
