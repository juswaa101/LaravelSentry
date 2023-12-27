@component('mail::message')
# Email Verification

Hello,

Please click the button below to verify your email address:

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Verify Email
@endcomponent

If you did not create an account, no further action is required.

Email verification link will expire in {{ config('auth.email_verification_expiration_time', 30) }} minute{{ config('auth.email_verification_expiration_time', 30) != 1 ? 's' : '' }}.

Thanks, <br />
{{ config('app.name') }}
@endcomponent
