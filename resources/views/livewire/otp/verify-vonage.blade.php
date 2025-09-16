<?php

declare(strict_types=1);

use App\Facades\Otp;
use App\Livewire\Concerns\HandlesOtpCooldown;
use App\Models\User as AuthUser;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    use HandlesOtpCooldown;

    public string $code = '';
    public ?string $phone = null;
    public ?AuthUser $user = null;

    public function mount(): void
    {
        try {
            $this->user = auth()->user();
            if (! $this->user) throw new RuntimeException('Unauthenticated');
            $this->phone = $this->user->phone;
            $this->initCooldown();
        } catch (\Throwable) {
            Flux::toast(text: __('Unable to initialize SMS verification'), variant: 'danger');
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        }
    }

    public function resend(): void
    {
        try {
            if ($this->beginCooldownIfNeeded()) return;
            Otp::send($this->user, 'vonage');
            $this->startCooldown();
            session()->flash('status', __('Verification code sent'));
        } catch (\Throwable) {
            Flux::toast(text: __('Failed to resend SMS code'), variant: 'danger');
        }
    }

    public function confirm(): void
    {
        try {
            $this->validate(['code' => ['required', 'digits:' . (int) config('one-time-passwords.password_length', 6)]]);
            Otp::confirm($this->user, $this->code);
            Otp::markPhoneVerified($this->user);
            Flux::toast(text: __('Phone verified'), variant: 'success');

            if ($this->user?->email && is_null($this->user?->email_verified_at)) {
                $this->redirectRoute('otp.verify', navigate: true);
                return;
            }
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        } catch (\Throwable) {
            Flux::toast(text: __('Invalid or expired code'), variant: 'danger');
        }
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
        <flux:heading level="2">{{ __('Verify your phone (SMS)') }}</flux:heading>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $phone }}</p>
    </div>

    <div class="mb-4">
        <x-auth-session-status class="text-center" :status="session('status')"/>
    </div>

    <x-auth.validation-errors class="mb-4"/>

    <div class="grid gap-4">
        <div class="flex items-center gap-3">
            <flux:button wire:click="resend" x-bind:disabled="cd > 0">
                <span x-show="cd === 0" x-cloak>{{ __('Send code') }}</span>
                <span x-show="cd > 0" x-cloak>{{ __('Resend in') }} <span x-text="cd"></span>s</span>
            </flux:button>
        </div>

        <div class="grid gap-2">
            <flux:label for="phone-code">{{ __('Verification code') }}</flux:label>
            <flux:input id="phone-code" wire:model.defer="code" inputmode="numeric" autocomplete="one-time-code"
                        maxlength="{{ (int) config('one-time-passwords.password_length', 6) }}"
                        placeholder="{{ str_repeat('•', (int) config('one-time-passwords.password_length', 6)) }}"
                        required/>
        </div>

        <div class="mt-2">
            <flux:button wire:click="confirm">{{ __('Verify and continue') }}</flux:button>
        </div>
    </div>
</div>
