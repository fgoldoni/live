<form wire:submit="loginWithPhone" class="flex flex-col gap-4" aria-label="{{ __('Phone + Password') }}">
    <flux:field>
        <flux:input.group>
            <flux:select variant="listbox" searchable wire:model="detectedCountry" class="max-w-fit" aria-label="{{ __('Country') }}">
                @foreach(collect(config('countries.supported'))->sortBy('name') as $code => $country)
                    <flux:select.option value="{{ $code }}">
                        {{ $country['emoji'] }} {{ $country['name'] }} ({{ $country['prefix'] }})
                    </flux:select.option>
                @endforeach
            </flux:select>


            <flux:input
                wire:model.defer="phone"
                type="tel"
                required
                autocomplete="phone"
                placeholder="{{ __('173 8779485') }}"
            />
        </flux:input.group>
        <flux:error name="phone" />
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
