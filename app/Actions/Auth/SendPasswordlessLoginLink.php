<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Events\Auth\MagicLinkRequested;
use App\Facades\Auth\MagicLinks;
use App\Models\User;
use Illuminate\Support\Str;

final readonly class SendPasswordlessLoginLink
{
    public function execute(string $email): void
    {
        $normalizedEmail = Str::lower($email);
        $user = User::query()->whereRaw('lower(email) = ?', [$normalizedEmail])->first();

        if (!$user) {
            return;
        }

        MagicLinks::generate($user);
        event(new MagicLinkRequested($user));
    }
}
