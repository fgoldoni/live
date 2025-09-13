<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\MagicLinkRequested;
use Illuminate\Support\Facades\Log;

class LogMagicLinkRequested
{
    public function handle(MagicLinkRequested $magicLinkRequested): void
    {
        Log::info('Magic link requested', [
            'user_id' => $magicLinkRequested->user->id,
            'email'   => $magicLinkRequested->user->email,
        ]);
    }
}
