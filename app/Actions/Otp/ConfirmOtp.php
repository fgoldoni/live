<?php

declare(strict_types=1);

namespace App\Actions\Otp;

use App\Events\OtpVerified;
use App\Models\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Validation\ValidationException;

final readonly class ConfirmOtp
{
    public function __construct(private Dispatcher $dispatcher)
    {
    }

    public function execute(User $user, string $code): void
    {
        $consumeOneTimePasswordResult = $user->consumeOneTimePassword($code);

        if (!$consumeOneTimePasswordResult->isOk()) {
            throw ValidationException::withMessages(['code' => $consumeOneTimePasswordResult->validationMessage()]);
        }

        $this->dispatcher->dispatch(new OtpVerified($user->id));
    }
}
