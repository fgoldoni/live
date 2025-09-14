<?php

namespace App\Contracts\Geo;

use Illuminate\Http\Request;

interface CountryResolver
{
    public function resolveIso2(?string $ip = null): ?string;
}
