@component('mail::message')

# Password Reset Request

Hello,

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => $resetUrl, 'color' => 'primary'])
    Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Thanks,
<p style="color: #555; font-weight: bold;">{{ config('app.name') }}</p>

<hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

<div style="background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
    <p style="margin: 0; font-size: 14px; color: #555; font-weight: bold;">
        Note: The password reset link below will expire in {{ config('auth.email_verification_expiration_time', 30) }} minute{{ config('auth.email_verification_expiration_time', 30) != 1 ? 's' : '' }}.
    </p>
</div>

@endcomponent
