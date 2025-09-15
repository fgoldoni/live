<?php

declare(strict_types=1);

namespace App\Actions\Otp;

use Illuminate\Contracts\Cache\Repository as CacheRepository;

final readonly class EnforceCooldown
{
    public function __construct(private CacheRepository $cacheRepository)
    {
    }

    public function remaining(int $userId): int
    {
        $until = $this->cacheRepository->get('otp:cooldown:' . $userId);

        if (!is_int($until)) {
            return 0;
        }

        $now = time();

        return max(0, $until - $now);
    }

    public function start(int $userId): void
    {
        $seconds = (int) config('one-time-passwords.resend_cooldown_seconds', 60);
        $until   = time() + $seconds;
        $this->cacheRepository->put('otp:cooldown:' . $userId, $until, $seconds);
    }
}
