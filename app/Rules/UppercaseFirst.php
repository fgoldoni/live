<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UppercaseFirst implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $first = mb_substr((string) $value, 0, 1);
        if ($first !== mb_strtoupper($first)) {
            $fail(__('The :attribute must start with an uppercase letter.', [
                'attribute' => $attribute,
            ]));
        }
    }
}
