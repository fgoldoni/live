<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class EnsureVerifiedWithOtp
{
    public function handle(Request $request, Closure $next): mixed
    {
        $authenticatedUser = $request->user();

        if (! $authenticatedUser) {
            return redirect()->route('login');
        }

        $emailNeedsVerification = $authenticatedUser->email && is_null($authenticatedUser->email_verified_at);
        $phoneNeedsVerification = $authenticatedUser->phone && is_null($authenticatedUser->phone_verified_at);

        if ($emailNeedsVerification || $phoneNeedsVerification) {
            return $request->expectsJson()
                ? abort(403, 'Verification required.')
                : redirect()->route('otp.verify');
        }

        return $next($request);
    }
}
