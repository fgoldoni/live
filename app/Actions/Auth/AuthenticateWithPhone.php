<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Contracts\Auth\PhoneNormalizer;
use App\Events\Auth\LoginFailed;
use App\Events\Auth\LoginSucceeded;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Session\Session;
use Illuminate\Validation\ValidationException;

final readonly class AuthenticateWithPhone
{
    public function __construct(
        private StatefulGuard $guard,
        private Session $session,
        private Dispatcher $events,
        private PhoneNormalizer $phones,
    ) {
    }

    public function execute(string $phone, string $password, bool $remember = false): User
    {
        $e164 = $this->phones->isE164($phone) ? $phone : $this->phones->toE164($phone);

        if (! $this->guard->attempt(['phone' => $e164, 'password' => $password], $remember)) {
            $this->events->dispatch(new LoginFailed(null));

            throw ValidationException::withMessages(['phone' => __('Authentication failed')]);
        }

        $this->session->regenerate();

        /** @var User $user */
        $user = $this->guard->user();
        $this->events->dispatch(new LoginSucceeded($user));

        return $user;
    }
}
