<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\VerifyMagicLinkAndLogin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final class VerifyAndConsumeMagicLinkController
{
    public function __invoke(Request $request, int $user, string $token): RedirectResponse
    {
        try {
            app(VerifyMagicLinkAndLogin::class)->execute($user, $token);

            return redirect()->intended(route('dashboard'));
        } catch (ValidationException $e) {
            if (auth()->check() && (int) auth()->id() === $user) {
                return redirect()->intended(route('dashboard'))
                    ->with('status', __('This magic link was already used. You are signed in.'));
            }

            report($e);

            return redirect()
                ->route('login')
                ->withErrors(['magic' => $e->errors()['token'][0] ?? __('This magic link is invalid or already used.')]);
        }
    }
}
