<form wire:submit="loginWithPhone" class="flex flex-col gap-4" aria-label="{{ __('Phone + Password') }}">
    <flux:field>
        <flux:input.group>
            <flux:select variant="listbox" searchable wire:model="detectedCountry" class="max-w-fit" aria-label="{{ __('Country') }}">
                <flux:select.option value="DE">🇩🇪 DE (+49)</flux:select.option>
                <flux:select.option value="FR">🇫🇷 FR (+33)</flux:select.option>
                <flux:select.option value="BE">🇧🇪 BE (+32)</flux:select.option>
                <flux:select.option value="CM">🇨🇲 CM (+237)</flux:select.option>
                <flux:select.option value="CI">🇨🇮 CI (+225)</flux:select.option>
                <flux:select.option value="US">🇺🇸 US (+1)</flux:select.option>
                <flux:select.option value="CA">🇨🇦 CA (+1)</flux:select.option>
            </flux:select>

            <flux:input
                wire:model.defer="phone"
                type="tel"
                required
                autocomplete="phone"
                placeholder="{{ __('173 8779485') }}"
            />
        </flux:input.group>
        <flux:error name="phone_full" />
    </flux:field>
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
