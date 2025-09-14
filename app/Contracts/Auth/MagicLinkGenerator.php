<?php

namespace App\Contracts\Auth;

use App\Models\User;

interface MagicLinkGenerator
{
    public function generate(User $user): string;
}
