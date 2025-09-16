<?php

declare(strict_types=1);

namespace App\Actions\Otp;

use App\Models\User;

final readonly class GetAllowedChannels
{
    /**
     * @return string[]
     */
    public function execute(User $user): array
    {
        $allowed = [];

        if ($user->email) {
            $allowed[] = 'mail';
        }

        if ($user->phone) {
            $allowed[] = 'vonage';
            $allowed[] = 'WhatsApp';
        }

        return $allowed;
    }
}
