<?php

declare(strict_types=1);

namespace App\Events;

final readonly class OtpSent
{
    public function __construct(
        public int $userId,
        public string $channel
    ) {
    }
}
