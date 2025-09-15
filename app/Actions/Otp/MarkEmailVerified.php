<?php

declare(strict_types=1);

namespace App\Actions\Otp;

use App\Models\User;

final readonly class MarkEmailVerified
{
    public function execute(User $user): void
    {
        $user->touch('email_verified_at');
    }
}
