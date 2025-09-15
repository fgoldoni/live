<?php

use App\Actions\Auth\AuthenticateWithPhone;
use App\Actions\Auth\SendPasswordlessLoginLink;
use App\Facades\Auth\Phone as PhoneFacade;
use App\Facades\Geo\Country;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Propaganistas\LaravelPhone\Rules\Phone;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $phone = '';
    public string $password = '';
    public bool $remember = false;
    public string $magicEmail = '';
    public string $method = 'phone';
    public string $detectedCountry = 'DE';

    public function mount(): void
    {
        $this->detectedCountry = Country::resolveIso2(request()->ip());
    }

    public function selectMethod(string $method): void
    {
        $this->method = $method;
    }

    public function loginWithPhone(): void
    {
        $this->phone = (string)(PhoneFacade::tryToE164($this->phone, $this->detectedCountry) ?? '');

        $this->validate([
            'phone' => [
                'required',
                'regex:/^\+[1-9]\d{1,14}$/',
                (new Phone)->international()->lenient(),
                Rule::exists('users', 'phone')->where(fn($q) => $q->whereNull('deleted_at')),
            ],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        app(AuthenticateWithPhone::class)->execute($this->phone, $this->password, $this->remember);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function sendMagic(): void
    {
        $this->validate([
            'magicEmail' => [
                'bail',
                'required',
                'string',
                'email:rfc,dns',
                Rule::exists('users', 'email')->where(fn($q) => $q->whereNull('deleted_at')),
            ],
        ]);

        app(SendPasswordlessLoginLink::class)->execute($this->magicEmail);

        session()->flash('status', __('If your account exists, a magic link has been sent'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="mb-4 text-center">
        <flux:heading level="2">{{ __('Log in to your account') }}</flux:heading>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Choose a sign-in method below') }}
        </p>
    </div>

    <div class="mb-4">
        <x-auth-session-status class="text-center" :status="session('status')"/>
    </div>

    <x-auth.validation-errors class="mb-4" />

    <div class="w-full">
        <flux:button.group class="w-full">
            <flux:button class="w-full" wire:click="selectMethod('phone')" :variant="$method === 'phone' ? 'primary' : null">
                {{ __('Phone + Password') }}
            </flux:button>
            <flux:button class="w-full" wire:click="selectMethod('magic')" :variant="$method === 'magic' ? 'primary' : null">
                {{ __('Email') }}
            </flux:button>
        </flux:button.group>
    </div>

    <div class="mt-4">
        <div x-data x-show="$wire.get('method') === 'phone'">
            @include('livewire.auth.partials.phone-login')
        </div>
        <div x-data x-show="$wire.get('method') === 'magic'">
            @include('livewire.auth.partials.magic-link-login')
        </div>
    </div>

    <div class="mt-6 flex items-center justify-between text-sm">
        @if (Route::has('password.request'))
            <flux:link :href="route('password.request')" wire:navigate aria-label="{{ __('Forgot password?') }}">
                {{ __('Forgot password?') }}
            </flux:link>
        @endif

        @if (Route::has('register'))
            <flux:link :href="route('register')" wire:navigate aria-label="{{ __('Create account') }}">
                {{ __('Create account') }}
            </flux:link>
        @endif
    </div>
</div>
