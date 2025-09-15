<?php

declare(strict_types=1);

namespace App\Actions\Otp;

use App\Events\OtpRequested;
use App\Events\OtpSent;
use App\Models\User;
use App\Notifications\CustomOneTimePasswordNotification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Validation\ValidationException;

final readonly class SendOtp
{
    public function __construct(
        private GetAllowedChannels $getAllowedChannels,
        private EnforceCooldown $enforceCooldown,
        private Dispatcher $dispatcher
    ) {
    }

    public function execute(User $user, string $channel): void
    {
        $allowed = $this->getAllowedChannels->execute($user);

        if (!in_array($channel, $allowed, true)) {
            throw ValidationException::withMessages(['channel' => __('Unsupported channel')]);
        }

        $left = $this->enforceCooldown->remaining($user->id);

        if ($left > 0) {
            throw ValidationException::withMessages(['channel' => __('Please wait before requesting a new code')]);
        }

        $this->dispatcher->dispatch(new OtpRequested($user->id, $channel));
        $oneTimePassword = $user->createOneTimePassword();
        $user->notify(new CustomOneTimePasswordNotification($oneTimePassword, [$channel]));
        $this->enforceCooldown->start($user->id);
        $this->dispatcher->dispatch(new OtpSent($user->id, $channel));
    }
}
