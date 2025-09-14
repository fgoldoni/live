@if ($errors->any())
    <flux:callout variant="danger" icon="exclamation-triangle" class="{{ $attributes->get('class', 'mb-4') }}">
        <flux:callout.heading>{{ __('Oops!') }}</flux:callout.heading>
        <flux:callout.text>
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </flux:callout.text>
    </flux:callout>
@endif
