@component('mail::message')
# {{ __('Sign in') }}

{{ __('Click the button below to sign in. This link will expire in 15 minutes.') }}

@component('mail::button', ['url' => $url])
{{ __('Sign in now') }}
@endcomponent

{{ __('If you did not request this email, you can safely ignore it.') }}

@endcomponent
