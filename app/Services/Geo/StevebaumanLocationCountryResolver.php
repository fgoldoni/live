<?php

declare(strict_types=1);

namespace App\Services\Geo;

use App\Contracts\Geo\CountryResolver;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;
use Throwable;

class StevebaumanLocationCountryResolver implements CountryResolver
{
    public function resolveIso2(?string $ip = null): ?string
    {
        $default = (string) config('countries.default', 'DE');

        try {
            $position = Location::get($ip);
            $code     = $position?->countryCode ?: $default;
        } catch (Throwable) {
            $code = $default;
        }

        return Str::upper($code);
    }
}
