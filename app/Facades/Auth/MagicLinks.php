<?php

declare(strict_types=1);

namespace App\Facades\Auth;

use App\Contracts\Auth\MagicLinkGenerator as MagicLinkGeneratorContract;
use Illuminate\Support\Facades\Facade;


final class MagicLinks extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MagicLinkGeneratorContract::class;
    }
}
