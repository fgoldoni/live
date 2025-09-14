<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Events\Auth\LoginFailed;
use App\Events\Auth\LoginSucceeded;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

readonly class AttemptLoginWithEmail
{
    public function __construct(private AuthManager $authManager)
    {
    }

    public function execute(string $email, string $password, bool $remember = false): void
    {
        $email = Str::lower($email);

        if (! $this->authManager->attempt(['email' => $email, 'password' => $password], $remember)) {
            $user = User::query()->whereRaw('lower(email) = ?', [$email])->first();
            event(new LoginFailed($user));
            throw ValidationException::withMessages([
                'email' => __('Authentication failed'),
            ]);
        }

        request()->session()->regenerate();
        event(new LoginSucceeded($this->authManager->user()));
    }
}
