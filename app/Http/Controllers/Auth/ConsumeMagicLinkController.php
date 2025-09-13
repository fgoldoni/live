<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ConsumeMagicLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConsumeMagicLinkController
{
    public function __invoke(Request $request, int $user, string $token): RedirectResponse
    {
        app(ConsumeMagicLink::class)->execute($user, $token);

        return redirect()->intended(route('dashboard'));
    }
}
