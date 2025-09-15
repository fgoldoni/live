<?php

declare(strict_types=1);

namespace App\Facades;

use App\Contracts\Otp\OtpManager;
use Illuminate\Support\Facades\Facade;

final class Otp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OtpManager::class;
    }
}
