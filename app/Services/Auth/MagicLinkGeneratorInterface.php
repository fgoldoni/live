<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

interface MagicLinkGeneratorInterface
{
    public function generate(User $user): string;
}
