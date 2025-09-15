<?php

declare(strict_types=1);

namespace App\Services\Otp;

use App\Actions\Otp\ConfirmOtp;
use App\Actions\Otp\EnforceCooldown;
use App\Actions\Otp\GetAllowedChannels;
use App\Actions\Otp\GetVerificationRequirements;
use App\Actions\Otp\MarkEmailVerified;
use App\Actions\Otp\MarkPhoneVerified;
use App\Actions\Otp\SendOtp;
use App\Contracts\Otp\OtpManager;
use App\Models\User;

final readonly class DefaultOtpManager implements OtpManager
{
    public function __construct(
        private GetAllowedChannels $getAllowedChannels,
        private EnforceCooldown $enforceCooldown,
        private SendOtp $sendOtp,
        private ConfirmOtp $confirmOtp,
        private MarkEmailVerified $markEmailVerified,
        private MarkPhoneVerified $markPhoneVerified,
        private GetVerificationRequirements $getVerificationRequirements
    ) {
    }

    /**
     * @return array<int,string>
     */
    public function allowedChannels(User $user): array
    {
        return $this->getAllowedChannels->execute($user);
    }

    public function remainingCooldown(int $userId): int
    {
        return $this->enforceCooldown->remaining($userId);
    }

    public function startCooldown(int $userId): void
    {
        $this->enforceCooldown->start($userId);
    }

    public function send(User $user, string $channel): void
    {
        $this->sendOtp->execute($user, $channel);
    }

    public function confirm(User $user, string $code): void
    {
        $this->confirmOtp->execute($user, $code);
    }

    public function markEmailVerified(User $user): void
    {
        $this->markEmailVerified->execute($user);
    }

    public function markPhoneVerified(User $user): void
    {
        $this->markPhoneVerified->execute($user);
    }

    /**
     * @return array{email:?string,phone:?string,needsEmail:bool,needsPhone:bool}
     */
    public function requirements(User $user): array
    {
        return $this->getVerificationRequirements->execute($user);
    }
}
