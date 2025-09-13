<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\LoginFailed;
use Illuminate\Support\Facades\Log;

class LogLoginFailed
{
    public function handle(LoginFailed $loginFailed): void
    {
        Log::warning('Login failed', [
            'user_id' => $loginFailed->user?->id,
            'email'   => $loginFailed->user?->email,
        ]);
    }
}
