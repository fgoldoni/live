<?php

declare(strict_types=1);

use App\Facades\Otp;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')]
class extends Component {
    public string $code = '';

    public int $cooldown = 0;

    public ?string $email = null;

    public function mount(): void
    {
        $user           = auth()->user();
        $this->email    = $user?->email;
        $this->cooldown = Otp::remainingCooldown($user->id);
    }

    public function resend(): void
    {
        $user = auth()->user();
        $left = Otp::remainingCooldown($user->id);

        if ($left > 0) {
            $this->cooldown = $left;
            $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);

            return;
        }

        Otp::send($user, 'mail');
        $this->cooldown = (int)config('one-time-passwords.resend_cooldown_seconds', 60);
        session()->flash('status', __('Verification code sent'));
        $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
    }

    public function confirm(): void
    {
        $this->validate(['code' => ['required', 'digits:' . (int)config('one-time-passwords.password_length', 6)]]);
        $user = auth()->user();
        Otp::confirm($user, $this->code);
        Otp::markEmailVerified($user);
        Flux::toast(text: __('Email verified'), variant: 'success');

        if ($user->phone && is_null($user->phone_verified_at)) {
            $this->redirectRoute('otp.verify', navigate: true);

            return;
        }

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
};
?>
<div
    x-data="{ cd: {{ (int) $cooldown }}, t: null, start() { if (this.t) return; this.t = setInterval(() => { if (this.cd > 0) { this.cd-- } else { clearInterval(this.t); this.t = null } }, 1000) } }"
    x-init="if (cd > 0) start()"
    x-on:otp-cooldown-started.window="cd = $event.detail.cooldown; start()"
    class="flex flex-col gap-6"
>
    <div class="mb-4 text-center">
        <flux:heading level="2">{{ __('Verify your email') }}</flux:heading>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $email }}</p>
    </div>

    <div class="mb-4">
        <x-auth-session-status class="text-center" :status="session('status')"/>
    </div>

    <x-auth.validation-errors class="mb-4"/>

    <div class="grid gap-4">
        <div class="flex items-center gap-3">
            <flux:button wire:click="resend" x-bind:disabled="cd > 0">
                <span x-show="cd === 0">{{ __('Send code') }}</span>
                <span x-show="cd > 0">{{ __('Resend in') }} <span x-text="cd"></span>s</span>
            </flux:button>
        </div>

        <div class="grid gap-2">
            <flux:label for="email-code">{{ __('Verification code') }}</flux:label>
            <flux:input id="email-code" wire:model.defer="code" inputmode="numeric" autocomplete="one-time-code"
                        maxlength="{{ (int) config('one-time-passwords.password_length', 6) }}"
                        placeholder="{{ str_repeat('•', (int) config('one-time-passwords.password_length', 6)) }}"
                        required/>
        </div>

        <div class="mt-2">
            <flux:button wire:click="confirm">{{ __('Verify and continue') }}</flux:button>
        </div>
    </div>
</div>
