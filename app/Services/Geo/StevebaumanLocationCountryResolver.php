<?php

declare(strict_types=1);

namespace App\Services\Geo;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

class StevebaumanLocationCountryResolver implements CountryResolverInterface
{
    public function resolve(Request $request): string
    {
        $position = Location::get($request->ip());
        $code     = $position?->countryCode ?: config('app.phone_default_country', 'DE');
        return Str::upper($code ?: 'DE');
    }
}
