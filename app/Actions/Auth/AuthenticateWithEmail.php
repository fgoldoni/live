<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Events\Auth\LoginFailed;
use App\Events\Auth\LoginSucceeded;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final readonly class AuthenticateWithEmail
{
    public function __construct(private StatefulGuard $guard) {}

    public function execute(string $email, string $password, bool $remember = false): User
    {
        $normalizedEmail = Str::lower($email);

        if (! $this->guard->attempt(['email' => $normalizedEmail, 'password' => $password], $remember)) {
            $maybeUser = User::query()->whereRaw('lower(email) = ?', [$normalizedEmail])->first();
            event(new LoginFailed($maybeUser));
            throw ValidationException::withMessages(['email' => __('Authentication failed')]);
        }

        Session::regenerate();

        $user = $this->guard->user();
        event(new LoginSucceeded($user));

        return $user;
    }
}
