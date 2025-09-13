<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\MagicLinkConsumed;
use Illuminate\Support\Facades\Log;

class LogMagicLinkConsumed
{
    public function handle(MagicLinkConsumed $magicLinkConsumed): void
    {
        Log::info('Magic link consumed', [
            'user_id' => $magicLinkConsumed->user->id,
            'email'   => $magicLinkConsumed->user->email,
        ]);
    }
}
