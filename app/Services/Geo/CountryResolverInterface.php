<?php

declare(strict_types=1);

namespace App\Services\Geo;

use Illuminate\Http\Request;

interface CountryResolverInterface
{
    public function resolve(Request $request): string;
}
