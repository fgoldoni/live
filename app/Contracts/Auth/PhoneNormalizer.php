<?php

declare(strict_types=1);

namespace App\Contracts\Auth;

interface PhoneNormalizer
{
    public function toE164(string $input, ?string $defaultCountry = null): string;

    public function tryToE164(string $input, ?string $defaultCountry = null): ?string;

    public function isE164(string $input): bool;
}
