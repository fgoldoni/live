<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\Auth\PhoneNormalizerInterface;
use Illuminate\Support\Facades\Facade;

final class PhoneNormalizer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PhoneNormalizerInterface::class;
    }
}
