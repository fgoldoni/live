<?php

use App\Actions\Auth\AttemptLoginWithEmail;
use App\Actions\Auth\AttemptLoginWithPhone;
use App\Actions\Auth\SendMagicLink;
use App\Facades\PhoneNormalizer;
use App\Http\Requests\Auth\EmailLoginRequest;
use App\Http\Requests\Auth\PhoneLoginRequest;
use App\Services\Auth\LibPhoneNormalizer;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Propaganistas\LaravelPhone\Rules\Phone;
use Stevebauman\Location\Facades\Location;


new #[Layout('components.layouts.auth')]
class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $phone = '+491738779485';
    public string $magic_email = '';
    public string $tab = 'phone';
    public string $detectedCountry = 'DE';

    public function mount(): void
    {
        $default = (string)config('app.phone_default_country', 'DE');

        try {
            $pos = Location::get(request()->ip());
            $this->detectedCountry = strtoupper($pos->countryCode ?? $default);
        } catch (\Throwable) {
            $this->detectedCountry = $default;
        }
    }

    public function updatedEmail(string $value): void
    {
        $this->email = strtolower($value);
    }

    public function updatedMagicEmail(string $value): void
    {
        $this->magic_email = strtolower($value);
    }

    public function loginWithEmail(): void
    {
        $this->validate([
            'email' => [
                'bail',
                'required',
                'string',
                'email:rfc,dns',
                Rule::exists('users', 'email')->where(fn($q) => $q->whereNull('deleted_at')),
            ],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        app(AttemptLoginWithEmail::class)
            ->execute($this->email, $this->password, $this->remember);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function loginWithPhone(): void
    {
        $this->phone = (string)(PhoneNormalizer::tryToE164($this->phone, $this->detectedCountry) ?? '');

        $this->validate([
            'phone' => [
                'required',
                'regex:/^\+[1-9]\d{1,14}$/',
                (new Phone)->international(),
                Rule::exists('users', 'phone')->where(fn ($q) => $q->whereNull('deleted_at')),
            ],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        app(AttemptLoginWithPhone::class)
            ->execute($this->phone, $this->password, $this->remember);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function sendMagic(): void
    {
        $this->validate([
            'magic_email' => [
                'bail',
                'required',
                'string',
                'email:rfc,dns',
                Rule::exists('users', 'email')->where(fn($q) => $q->whereNull('deleted_at')),
            ],
        ]);

        app(SendMagicLink::class)->execute($this->magic_email);

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

    <flux:tab.group wire:model="tab" aria-label="{{ __('Authentication methods') }}">
        <flux:tabs variant="segmented">
            <flux:tab name="phone" class="cursor-pointer">
                {{ __('Phone + Password') }}
            </flux:tab>

            <flux:tab name="magic" class="cursor-pointer">
                {{ __('Magic link') }}
            </flux:tab>

            <flux:tab name="email" class="cursor-pointer">
                {{ __('Email + Password') }}
            </flux:tab>
        </flux:tabs>

        <flux:tab.panel name="phone">
            @include('livewire.auth.partials.phone-login')
        </flux:tab.panel>

        <flux:tab.panel name="magic">
            @include('livewire.auth.partials.magic-link-login')
        </flux:tab.panel>

        <flux:tab.panel name="email">
            @include('livewire.auth.partials.email-login')
        </flux:tab.panel>
    </flux:tab.group>


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
