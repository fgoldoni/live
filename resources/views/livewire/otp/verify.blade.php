<?php

declare(strict_types=1);

use App\Facades\Otp;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public ?string $email = null;
    public ?string $phone = null;
    public bool $needsEmail = false;
    public bool $needsPhone = false;
    public int $cooldown = 0;

    public function mount(): void
    {
        $req = Otp::requirements(auth()->user());
        $this->email = $req['email'];
        $this->phone = $req['phone'];
        $this->needsEmail = $req['needsEmail'];
        $this->needsPhone = $req['needsPhone'];
        $this->cooldown = Otp::remainingCooldown(auth()->id());
    }

    public function verifyEmail(): void
    {
        if (!$this->needsEmail) {
            return;
        }
        $left = Otp::remainingCooldown(auth()->id());
        if ($left > 0) {
            $this->cooldown = $left;
            $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
            Flux::toast(variant: 'warning', text: __('Please wait before requesting a new code'));
            return;
        }
        Otp::send(auth()->user(), 'mail');
        $this->cooldown = (int) config('one-time-passwords.resend_cooldown_seconds', 60);
        $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
        Flux::toast(variant: 'success', text: __('Verification code sent to email'));
        $this->redirectRoute('otp.verify-email', navigate: true);
    }

    public function verifyPhoneSms(): void
    {
        if (!$this->needsPhone) {
            return;
        }
        $left = Otp::remainingCooldown(auth()->id());
        if ($left > 0) {
            $this->cooldown = $left;
            $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
            Flux::toast(variant: 'warning', text: __('Please wait before requesting a new code'));
            return;
        }
        Otp::send(auth()->user(), 'vonage');
        $this->cooldown = (int) config('one-time-passwords.resend_cooldown_seconds', 60);
        $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
        Flux::toast(variant: 'success', text: __('Verification code sent by SMS'));
        $this->redirectRoute('otp.verify-phone', navigate: true);
    }

    public function verifyPhoneWhatsapp(): void
    {
        if (!$this->needsPhone) {
            return;
        }
        $left = Otp::remainingCooldown(auth()->id());
        if ($left > 0) {
            $this->cooldown = $left;
            $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
            Flux::toast(variant: 'warning', text: __('Please wait before requesting a new code'));
            return;
        }
        Otp::send(auth()->user(), 'meta_wa');
        $this->cooldown = (int) config('one-time-passwords.resend_cooldown_seconds', 60);
        $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
        Flux::toast(variant: 'success', text: __('Verification code sent via WhatsApp'));
        $this->redirectRoute('otp.verify-phone', navigate: true);
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
        <flux:heading level="2">{{ __('Verify your contact details') }}</flux:heading>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Choose what to verify') }}</p>
    </div>

    <div class="grid gap-4">
        @if($needsEmail)
            <div class="flex items-center justify-between rounded-lg border p-4">
                <div class="text-sm">
                    <div class="font-medium">{{ __('Email') }}</div>
                    <div class="text-zinc-600 dark:text-zinc-400">{{ $email }}</div>
                </div>
                <flux:button wire:click="verifyEmail" x-bind:disabled="cd > 0">
                    <span x-show="cd === 0">{{ __('Verify') }}</span>
                    <span x-show="cd > 0">{{ __('Resend in') }} <span x-text="cd"></span>s</span>
                </flux:button>
            </div>
        @endif

        @if($needsPhone)
            <div class="rounded-lg border p-4 space-y-3">
                <div class="text-sm">
                    <div class="font-medium">{{ __('Phone') }}</div>
                    <div class="text-zinc-600 dark:text-zinc-400">{{ $phone }}</div>
                </div>
                <div class="flex gap-3">
                    <flux:button wire:click="verifyPhoneSms" x-bind:disabled="cd > 0">
                        <span x-show="cd === 0">{{ __('Verify by SMS') }}</span>
                        <span x-show="cd > 0">{{ __('Resend in') }} <span x-text="cd"></span>s</span>
                    </flux:button>
                    <flux:button wire:click="verifyPhoneWhatsapp" x-bind:disabled="cd > 0">
                        <span x-show="cd === 0">{{ __('Verify by WhatsApp') }}</span>
                        <span x-show="cd > 0">{{ __('Resend in') }} <span x-text="cd"></span>s</span>
                    </flux:button>
                </div>
            </div>
        @endif
    </div>
</div>

