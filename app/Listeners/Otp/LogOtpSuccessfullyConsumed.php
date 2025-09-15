<?php

declare(strict_types=1);

namespace App\Listeners\Otp;

use Illuminate\Support\Facades\Log;

final class LogOtpSuccessfullyConsumed
{
    public function handle(): void
    {
        Log::info('otp_consumed');
    }
}
