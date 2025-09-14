<?php

declare(strict_types=1);

namespace App\Contracts\Auth;

use App\Models\User;

interface MagicLinkGenerator
{
    public function generate(User $user): string;
}
