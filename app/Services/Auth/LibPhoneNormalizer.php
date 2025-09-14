<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Contracts\Auth\PhoneNormalizer;
use Illuminate\Support\Str;
use Throwable;

final readonly class LibPhoneNormalizer implements PhoneNormalizer
{
    public function __construct(private ?string $fallbackCountry = null) {}

    public function isE164(string $input): bool
    {
        return (bool) preg_match('/^\+[1-9]\d{1,14}$/', trim($input));
    }

    public function toE164(string $input, ?string $defaultCountry = null): string
    {
        $country = Str::upper($defaultCountry ?: $this->fallbackCountry ?: (string) config('countries.default', 'DE'));
        return phone($input, $country)->formatE164();
    }

    public function tryToE164(string $input, ?string $defaultCountry = null): ?string
    {
        $trimmed = trim($input);
        if ($trimmed === '') {
            return null;
        }

        if ($this->isE164($trimmed)) {
            return $trimmed;
        }

        try {
            return $this->toE164($trimmed, $defaultCountry);
        } catch (Throwable) {
            return null;
        }
    }
}
