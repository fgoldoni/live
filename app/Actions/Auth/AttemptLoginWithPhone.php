<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Events\Auth\LoginFailed;
use App\Events\Auth\LoginSucceeded;
use App\Services\Auth\PhoneNormalizerInterface;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Validation\ValidationException;

final readonly class AttemptLoginWithPhone
{
    public function __construct(
        private AuthFactory              $authFactory,
        private PhoneNormalizerInterface $phoneNormalizer,
    ) {
    }


    public function execute(string $phoneE164, string $password, bool $remember = false, ?string $guardName = null): void
    {
        $normalized = $this->phoneNormalizer->normalize($phoneE164);

        $guard = $this->authFactory->guard($guardName ?? config('auth.defaults.guard', 'web'));

        if (! $guard->attempt(['phone' => $normalized, 'password' => $password], $remember)) {
            event(new LoginFailed(null));

            throw ValidationException::withMessages([
                'phone_full' => __('Authentication failed'),
            ]);
        }

        request()->session()->regenerate();

        event(new LoginSucceeded($guard->user()));
    }
}
