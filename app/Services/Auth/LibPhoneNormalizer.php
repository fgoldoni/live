<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Illuminate\Support\Str;
use Throwable;

readonly class LibPhoneNormalizer implements PhoneNormalizerInterface
{
    public function __construct(private ?string $fallbackCountry = null)
    {
    }

    public function normalize(string $input, ?string $defaultCountry = null): string
    {
        $country = Str::upper($defaultCountry ?: $this->fallbackCountry ?: config('app.phone_default_country', 'DE'));
        return phone($input, $country)->formatE164();
    }

    public function tryToE164(string $input, ?string $defaultCountry = null): ?string
    {
        if (trim($input) === '') {
            return null;
        }

        $country = Str::upper($defaultCountry ?: $this->fallbackCountry ?: config('app.phone_default_country', 'DE'));

        try {
            return phone($input, $country)->formatE164();
        } catch (Throwable) {
            return null;
        }
    }
}
