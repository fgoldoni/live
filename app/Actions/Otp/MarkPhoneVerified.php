<?php

declare(strict_types=1);

namespace App\Actions\Otp;

use App\Models\User;

final readonly class MarkPhoneVerified
{
    public function execute(User $user): void
    {
        $user->touch('phone_verified_at');
    }
}
