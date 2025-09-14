<?php

declare(strict_types=1);

namespace App\Facades\Auth;

use App\Contracts\Auth\PhoneNormalizer as PhoneNormalizerContract;
use Illuminate\Support\Facades\Facade;

final class Phone extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PhoneNormalizerContract::class;
    }
}
