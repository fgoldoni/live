<?php

declare(strict_types=1);

use App\Facades\Otp;
use App\Models\User as AuthUser;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public ?string $email = null;
    public ?string $phone = null;
    public bool $needsEmail = false;
    public bool $needsPhone = false;
    public bool $onlyWhatsApp = false;
    public int $cooldown = 0;
    public ?AuthUser $user = null;
    public int $userId = 0;

    public function mount(): void
    {
        try {
            $this->user = auth()->user();
            $this->userId = (int) ($this->user?->getAuthIdentifier() ?? 0);
            if (! $this->userId) throw new RuntimeException('Unauthenticated');

            $req = Otp::requirements($this->user);
            $this->email        = $req['email'];
            $this->phone        = $req['phone'];
            $this->needsEmail   = $req['needsEmail'];
            $this->needsPhone   = $req['needsPhone'];
            $this->onlyWhatsApp = $req['onlyWhatsApp'];

            $this->cooldown = Otp::remainingCooldown($this->userId);
        } catch (\Throwable $e) {
            Flux::toast(text: __('Unable to initialize verification'), variant: 'danger');
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        }
    }

    public function verifyEmail(): void
    {
        if (! $this->needsEmail) return;
        $this->sendAndRedirect('mail', 'otp.verify-email', __('Verification code sent to email'));
    }

    public function verifyPhoneSms(): void
    {
        if (! $this->needsPhone) return;
        if ($this->onlyWhatsApp) {
            Flux::toast(text: __('SMS verification is not available for your region. Please use WhatsApp.'), variant: 'warning');
            return;
        }
        $this->sendAndRedirect('vonage', 'otp.verify-vonage', __('Verification code sent by SMS'));
    }

    public function verifyPhoneWhatsApp(): void
    {
        if (! $this->needsPhone) return;
        $this->sendAndRedirect('WhatsApp', 'otp.verify-whatsapp', __('Verification code sent via WhatsApp'));
    }

    private function sendAndRedirect(string $channel, string $route, string $successMsg): void
    {
        try {
            if ($this->beginCooldownIfNeeded()) return;
            Otp::send($this->user, $channel);
            $this->startCooldown();
            Flux::toast(text: $successMsg, variant: 'success');
            $this->redirectRoute($route, navigate: true);
        } catch (\Throwable $e) {

            dd($e->getMessage());
            Flux::toast(text: __('Failed to send verification code'), variant: 'danger');
        }
    }

    private function beginCooldownIfNeeded(): bool
    {
        try {
            $left = Otp::remainingCooldown($this->userId);
            if ($left <= 0) return false;
            $this->cooldown = $left;
            $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
            Flux::toast(text: __('Please wait before requesting a new code'), variant: 'warning');
            return true;
        } catch (\Throwable $e) {
            Flux::toast(text: __('Cooldown check failed'), variant: 'danger');
            return true;
        }
    }

    private function startCooldown(): void
    {
        $this->cooldown = (int) config('one-time-passwords.resend_cooldown_seconds', 60);
        $this->dispatch('otp-cooldown-started', cooldown: $this->cooldown);
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
                <flux:button class="w-full sm:w-auto" wire:click="verifyEmail" x-bind:disabled="cd > 0">
                    <span x-show="cd === 0" x-cloak>{{ __('Verify') }}</span>
                    <span x-show="cd > 0" x-cloak>{{ __('Resend in') }} <span x-text="cd"></span>s</span>
                </flux:button>
            </div>
        @endif

        @if($needsPhone)
            <div class="rounded-lg border p-4 space-y-3">
                <div class="text-sm">
                    <div class="font-medium">{{ __('Phone') }}</div>
                    <div class="text-zinc-600 dark:text-zinc-400">{{ $phone }}</div>
                </div>

                @if($onlyWhatsApp)
                    <flux:button class="w-full" wire:click="verifyPhoneWhatsApp" x-bind:disabled="cd > 0">
                        <span x-show="cd === 0" x-cloak>{{ __('Verify by WhatsApp') }}</span>
                        <span x-show="cd > 0" x-cloak>{{ __('Resend in') }} <span x-text="cd"></span>s</span>
                    </flux:button>
                @else
                    <flux:button.group class="w-full">
                        <flux:button class="w-full" wire:click="verifyPhoneSms" x-bind:disabled="cd > 0">
                            <span x-show="cd === 0" x-cloak>{{ __('Verify by SMS') }}</span>
                            <span x-show="cd > 0" x-cloak>{{ __('Resend in') }} <span x-text="cd"></span>s</span>
                        </flux:button>
                        <flux:button class="w-full" wire:click="verifyPhoneWhatsApp" x-bind:disabled="cd > 0">
                            <span x-show="cd === 0" x-cloak>{{ __('Verify by WhatsApp') }}</span>
                            <span x-show="cd > 0" x-cloak>{{ __('Resend in') }} <span x-text="cd"></span>s</span>
                        </flux:button>
                    </flux:button.group>
                @endif
            </div>
        @endif
    </div>
</div>
