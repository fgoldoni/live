<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Contracts\Auth\PhoneNormalizer;
use App\Events\Auth\LoginFailed;
use App\Events\Auth\LoginSucceeded;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;

final readonly class AuthenticateWithPhone
{
    public function __construct(
        private StatefulGuard $guard,
        private PhoneNormalizer $phoneNormalizer,
    ) {}

    public function execute(string $phone, string $password, bool $remember = false): object
    {
        $phoneE164 = $this->phoneNormalizer->isE164($phone)
            ? $phone
            : $this->phoneNormalizer->toE164($phone);

        if (! $this->guard->attempt(['phone' => $phoneE164, 'password' => $password], $remember)) {
            event(new LoginFailed(null));
            throw ValidationException::withMessages(['phone_full' => __('Authentication failed')]);
        }

        request()->session()->regenerate();

        $user = $this->guard->user();
        event(new LoginSucceeded($user));

        return $user;
    }
}
