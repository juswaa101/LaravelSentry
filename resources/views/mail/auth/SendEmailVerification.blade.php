@component('mail::message')

# Email Verification

Hello,

Please click the button below to verify your email address:

@component('mail::button', ['url' => $url, 'color' => 'primary'])
    Verify Email
@endcomponent

If you did not create an account, no further action is required.

Thanks,
<p style="color: #555; font-weight: bold;">{{ config('app.name') }}</p>

<hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

<div style="background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
    <p style="margin: 0; font-size: 14px; color: #555; font-weight: bold;">
        Note: Email verification link will expire in {{ config('auth.email_verification_expiration_time', 30) }} minute{{ config('auth.email_verification_expiration_time', 30) != 1 ? 's' : '' }}.
    </p>
</div>

@endcomponent
