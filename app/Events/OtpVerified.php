<?php

declare(strict_types=1);

namespace App\Events;

final readonly class OtpVerified
{
    public function __construct(public int $userId)
    {
    }
}
