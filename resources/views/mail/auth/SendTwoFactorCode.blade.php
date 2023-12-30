@component('mail::message')
# Two-Factor Authentication Code

Your two-factor authentication code is:

@component('mail::panel')
    <ul>
        @foreach (json_decode($two_factor_codes) as $two_factor_code)
            <li>{{ $two_factor_code }}</li>
        @endforeach
    </ul>
@endcomponent

Please copy the any two factor code above and paste it into the two factor field on the verification page

If you did not request a code, no further action is required.

Thanks, <br />
{{ config('app.name') }}
@endcomponent
