<?php

declare(strict_types=1);

namespace App\Services\Auth;

interface PhoneNormalizerInterface
{
    public function normalize(string $input, ?string $defaultCountry = null): string;

    public function tryToE164(string $input, ?string $defaultCountry = null): ?string;
}
