<?php

declare(strict_types=1);

namespace App\Contracts\Otp;

use App\Models\User;

interface OtpManager
{
    /**
     * @return array<int,string>
     */
    public function allowedChannels(User $user): array;

    public function remainingCooldown(int $userId): int;

    public function startCooldown(int $userId): void;

    public function send(User $user, string $channel): void;

    public function confirm(User $user, string $code): void;

    public function markAccountVerified(User $user): void;
}
