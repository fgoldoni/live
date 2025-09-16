<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Facades\Otp;
use Flux\Flux;

trait HandlesOtpCooldown
{
    public int $cooldown = 0;
    public int $userId = 0;

    protected function initCooldown(): void
    {
        $this->userId = (int) (auth()->id() ?? 0);
        if ($this->userId) {
            try {
                $this->cooldown = Otp::remainingCooldown($this->userId);
            } catch (\Throwable) {
                $this->cooldown = 0;
            }
        }
    }

    protected function beginCooldownIfNeeded(): bool
    {
        try {
            $left = Otp::remainingCooldown($this->userId);
            if ($left <= 0) return false;
            $this->cooldown = $left;
            $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
            Flux::toast(text: __('Please wait before requesting a new code'), variant: 'warning');
            return true;
        } catch (\Throwable) {
            Flux::toast(text: __('Cooldown check failed'), variant: 'error');
            return true;
        }
    }

    protected function startCooldown(): void
    {
        $this->cooldown = (int) config('one-time-passwords.resend_cooldown_seconds', 60);
        $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
    }
}
