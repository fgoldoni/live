<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Events\Auth\MagicLinkConsumed;
use App\Models\PasswordlessToken;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConsumeMagicLink
{
    private readonly StatefulGuard $statefulGuard;

    public function __construct(AuthManager $authManager)
    {
        $this->statefulGuard = $authManager->guard(config('auth.defaults.guard', 'web'));
    }

    public function execute(int $userId, string $plainToken): User
    {
        $user   = User::query()->findOrFail($userId);
        $hashed = hash('sha256', $plainToken);

        return DB::transaction(function () use ($user, $hashed): User {
            $token = PasswordlessToken::query()
                ->where('user_id', $user->id)
                ->where('token', $hashed)
                ->whereNull('used_at')
                ->where('expires_at', '>', CarbonImmutable::now())
                ->lockForUpdate()
                ->first();

            if (! $token) {
                throw ValidationException::withMessages([
                    'token' => __('Authentication failed'),
                ]);
            }

            $token->forceFill(['used_at' => CarbonImmutable::now()])->save();

            $this->statefulGuard->login($user, remember: true);
            request()->session()->regenerate();

            event(new MagicLinkConsumed($user));

            return $user;
        });
    }
}
