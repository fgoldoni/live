<?php

declare(strict_types=1);

namespace App\Contracts\Geo;

interface CountryResolver
{
    public function resolveIso2(?string $ip = null): ?string;
}
