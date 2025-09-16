<?php

declare(strict_types=1);

namespace App\Actions\Otp;

use App\Models\User;

final readonly class GetVerificationRequirements
{
    /**
     * @return array{email:?string,phone:?string,needsEmail:bool,needsPhone:bool}
     */
    public function execute(User $user): array
    {
        return [
            'email'      => $user->email,
            'phone'      => $user->phone,
            'needsEmail' => $user->email !== null && $user->email_verified_at === null,
            'needsPhone' => $user->phone !== null && $user->phone_verified_at === null,
            'onlyWhatsApp' => $user->is_african_phone,
        ];
    }
}
