@component('mail::message')
# Password Reset Notification

Hello,

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => $resetUrl])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Password reset link will expire in {{ config('auth.email_verification_expiration_time', 30) }} minute{{ config('auth.email_verification_expiration_time', 30) != 1 ? 's' : '' }}.

Thanks, <br/>
{{ config('app.name') }}
@endcomponent
