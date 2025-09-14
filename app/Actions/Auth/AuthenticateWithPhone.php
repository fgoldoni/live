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
        private StatefulGuard $statefulGuard,
        private Session $session,
        private Dispatcher $dispatcher,
        private PhoneNormalizer $phoneNormalizer,
    ) {
    }

    public function execute(string $phone, string $password, bool $remember = false): User
    {
        $e164 = $this->phoneNormalizer->isE164($phone) ? $phone : $this->phoneNormalizer->toE164($phone);

        if (! $this->statefulGuard->attempt(['phone' => $e164, 'password' => $password], $remember)) {
            $this->dispatcher->dispatch(new LoginFailed(null));

            throw ValidationException::withMessages(['phone' => __('Authentication failed')]);
        }

        $this->session->regenerate();

        /** @var User $user */
        $user = $this->statefulGuard->user();
        $this->dispatcher->dispatch(new LoginSucceeded($user));

        return $user;
    }
}
