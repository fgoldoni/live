<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Events\Auth\LoginFailed;
use App\Events\Auth\LoginSucceeded;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final readonly class AuthenticateWithEmail
{
    public function __construct(
        private StatefulGuard $statefulGuard,
        private Session $session,
        private Dispatcher $dispatcher,
    ) {
    }

    public function execute(string $email, string $password, bool $remember = false): User
    {
        $normalizedEmail = Str::lower($email);

        if (! $this->statefulGuard->attempt(['email' => $normalizedEmail, 'password' => $password], $remember)) {
            $maybeUser = User::query()->whereRaw('lower(email) = ?', [$normalizedEmail])->first();
            $this->dispatcher->dispatch(new LoginFailed($maybeUser));

            throw ValidationException::withMessages(['email' => __('Authentication failed')]);
        }

        $this->session->regenerate();

        /** @var User $user */
        $user = $this->statefulGuard->user();
        $this->dispatcher->dispatch(new LoginSucceeded($user));

        return $user;
    }
}
