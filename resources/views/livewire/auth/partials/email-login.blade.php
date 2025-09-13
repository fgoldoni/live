<form wire:submit="loginWithEmail" class="flex flex-col gap-4" aria-label="{{ __('Email + Password') }}">
    <flux:input
        wire:model="email"
        :label="__('Email address')"
        type="email"
        required
        autofocus
        autocomplete="email"
        placeholder="email@example.com"
    />

    <flux:input
        wire:model.defer="password"
        :label="__('Password')"
        type="password"
        required
        autocomplete="current-password"
        :placeholder="__('Password')"
        viewable
    />

    <flux:checkbox wire:model="remember" :label="__('Remember me')" />

    <flux:button type="submit" variant="primary" class="w-full">
        {{ __('Log in') }}
    </flux:button>
</form>
