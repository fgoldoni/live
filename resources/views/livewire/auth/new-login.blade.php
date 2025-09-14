<?php

use App\Actions\Auth\AuthenticateWithEmail;
use App\Actions\Auth\AuthenticateWithPhone;
use App\Actions\Auth\SendPasswordlessLoginLink;
use App\Facades\Auth\Phone as PhoneFacade;
use App\Facades\Geo\Country;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Propaganistas\LaravelPhone\Rules\Phone;


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
//        $user = User::first();
//        $buyer = User::find(2);
//        $seller = User::find(3);
//        Wallet::for($user)->label('main')->currency('EUR')->credit('100.00');
//        Wallet::for($buyer)->label('main')->currency('EUR')->credit('100.00');
//        Wallet::for($user)->label('main')->currency('EUR')->debit('25.00');
//        Wallet::for($buyer)->label('main')->currency('EUR')->transfer($seller, '35.00', ['status' => TransferStatus::PENDING]);
//        $entries = Wallet::for($user)->label('main')->currency('EUR')->history();
//        Wallet::for($buyer)
//            ->label('main')->currency('EUR')
//            ->transfer($seller, '35.00', ['status' => 'pending']);
//        $balance = Wallet::for($user)->label('main')->currency('EUR')->balance();
//        $wallets = Wallet::for($user)->wallets();
//        $totals = Wallet::for($user)->totalBalanceByCurrency();
//
        $this->detectedCountry = Country::resolveIso2(request()->ip());
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

        app(AuthenticateWithEmail::class)
            ->execute($this->email, $this->password, $this->remember);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
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

        app(AuthenticateWithPhone::class)
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

        app(SendPasswordlessLoginLink::class)->execute($this->magic_email);

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
