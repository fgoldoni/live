<form wire:submit="sendMagic" class="flex flex-col gap-4" aria-label="{{ __('Magic link (passwordless)') }}">
    <flux:input
        wire:model="magic_email"
        :label="__('Email address')"
        type="email"
        required
        autofocus
        autocomplete="email"
        placeholder="email@example.com"
    />

    <flux:button type="submit" variant="primary" class="w-full">
        {{ __('Send magic link') }}
    </flux:button>
</form>
