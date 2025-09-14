<?php

declare(strict_types=1);

namespace App\Facades\Geo;

use App\Contracts\Geo\CountryResolver as CountryResolverContract;
use Illuminate\Support\Facades\Facade;


final class Country extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CountryResolverContract::class;
    }
}
