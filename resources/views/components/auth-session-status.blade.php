@props([
    'status',
])

@if ($status)
    <flux:callout variant="success" icon="information-circle" heading="{{ $status }}" />
@endif
