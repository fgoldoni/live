<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\LoginSucceeded;
use Illuminate\Support\Facades\Log;

class LogLoginSucceeded
{
    public function handle(LoginSucceeded $loginSucceeded): void
    {
        Log::info('Login succeeded', [
            'user_id' => $loginSucceeded->user?->id,
            'email'   => $loginSucceeded->user?->email,
        ]);
    }
}
