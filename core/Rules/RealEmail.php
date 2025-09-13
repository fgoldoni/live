<?php

namespace Core\Rules;

use Illuminate\Contracts\Validation\Rule;

class RealEmail implements Rule
{
    #[\Override]
    public function passes($attribute, $value): bool
    {
        return ! preg_match('/^([a-z0-9\\+_\\-]+)(\\.[a-z0-9\\+_\\-]+)*@([a-z0-9\\-]+\\.)+[a-z]{2,6}$/ix', (string) $value)
            ? false
            : true;
    }

    #[\Override]
    public function message(): string
    {
        return __('This Email is not a valid email address.');
    }
}
