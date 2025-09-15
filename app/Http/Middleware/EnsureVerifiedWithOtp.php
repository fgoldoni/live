<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class EnsureVerifiedWithOtp
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (($user->email && is_null($user->email_verified_at)) || ($user->phone && is_null($user->phone_verified_at))) {
            return $request->expectsJson() ? abort(403, 'Verification required.') : redirect()->route('otp.verify');
        }

        return $next($request);
    }
}
