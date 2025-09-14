<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Events\Auth\MagicLinkConsumed;
use App\Models\PasswordlessToken;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Connection;
use Illuminate\Validation\ValidationException;

final readonly class VerifyMagicLinkAndLogin
{
    public function __construct(
        private StatefulGuard $statefulGuard,
        private Session $session,
        private Connection $connection,
        private Dispatcher $dispatcher,
    ) {
    }

    public function execute(int $userId, string $plainToken): User
    {
        $user        = User::query()->findOrFail($userId);
        $hashedToken = hash('sha256', $plainToken);

        return $this->connection->transaction(function () use ($user, $hashedToken): User {
            $token = PasswordlessToken::query()
                ->where('user_id', $user->id)
                ->where('token', $hashedToken)
                ->lockForUpdate()
                ->first();

            if (! $token) {
                throw ValidationException::withMessages(['token' => __('Invalid magic link.')]);
            }

            if ($token->used_at !== null) {
                throw ValidationException::withMessages(['token' => __('This magic link was already used.')]);
            }

            if ($token->expires_at <= CarbonImmutable::now()) {
                throw ValidationException::withMessages(['token' => __('This magic link has expired.')]);
            }

            $token->forceFill(['used_at' => CarbonImmutable::now()])->save();

            $this->statefulGuard->login($user, remember: true);
            $this->session->regenerate();

            $this->dispatcher->dispatch(new MagicLinkConsumed($user));

            return $user;
        });
    }
}
