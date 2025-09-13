<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Events\Auth\MagicLinkRequested;
use App\Models\User;
use App\Services\Auth\MagicLinkGeneratorInterface;
use Illuminate\Support\Str;

class SendMagicLink
{
    public function __construct(private readonly MagicLinkGeneratorInterface $magicLinkGenerator)
    {
    }

    public function execute(string $email): void
    {
        $email = Str::lower($email);
        $user  = User::query()->whereRaw('lower(email) = ?', [$email])->first();
        if ($user) {
            $this->magicLinkGenerator->generate($user);
            event(new MagicLinkRequested($user));
        }
    }
}
