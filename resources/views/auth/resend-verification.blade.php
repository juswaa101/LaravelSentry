<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resent Verification</title>
    @include('partials.header')
</head>

<body>
    @include('partials.navbar')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="card-title">Resend Verification Link</h3>
                    </div>
                    <div class="card-body">
                        <form id="resentVerificationForm">
                            <div class="form-group">
                                <span class="fw-bold">Hi, {{ auth()->user()->name }}.</span>
                                <p class="mt-3">
                                    We sent you an email with a verification link. Before proceeding, please check your
                                    email for a verification link.
                                </p>
                                <button type="button" class="btn btn-danger" id="resendBtn">Resend Verification
                                    Link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/auth/resent-verification.js') }}"></script>
</body>

</html>
