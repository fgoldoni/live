<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Contracts\Auth\MagicLinkGenerator;
use App\Mail\Auth\MagicLinkMail;
use App\Models\PasswordlessToken;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

final readonly class SignedUrlMagicLinkGenerator implements MagicLinkGenerator
{
    public function __construct(
        private Mailer $mailer,
        private int $ttlMinutes = 15
    ) {
    }

    public function generate(User $user): string
    {
        $plainToken = Str::random(64);
        $hash = hash('sha256', $plainToken);
        $expiresAt = CarbonImmutable::now()->addMinutes($this->ttlMinutes);

        PasswordlessToken::query()->create([
            'user_id' => $user->id,
            'token' => $hash,
            'expires_at' => $expiresAt,
            'metadata' => [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
        ]);

        $url = URL::temporarySignedRoute('auth.magic.consume', $expiresAt, [
            'user' => $user->getKey(),
            'token' => $plainToken,
        ]);

        $this->mailer->to($user->email)->queue(new MagicLinkMail($url));

        return $url;
    }
}
