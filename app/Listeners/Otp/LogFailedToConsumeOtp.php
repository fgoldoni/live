<?php

declare(strict_types=1);

namespace App\Listeners\Otp;

use Illuminate\Support\Facades\Log;
use Spatie\OneTimePasswords\Events\FailedToConsumeOneTimePassword;

final class LogFailedToConsumeOtp
{
    public function handle(FailedToConsumeOneTimePassword $failedToConsumeOneTimePassword): void
    {
        Log::warning('otp_failed', [
            'user_id' => $failedToConsumeOneTimePassword->user->getAuthIdentifier(),
            'reason'  => $failedToConsumeOneTimePassword->validationResult->name,
        ]);
    }
}
