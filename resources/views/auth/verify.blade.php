<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Account</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.header')
</head>

<body>
    @include('partials.navbar')

    <div class="container p-5">
        <div class="row">
            <div class="col-md-6 mx-auto" style="margin-bottom: 2rem;">
                <div class="card">
                    <div class="card-body">
                        <h1 class="mb-3">Verify Account Here</h1>
                        <div id="showMessage"></div>
                        <form id="verifyForm">
                            <input type="hidden" id="token" name="token" value="{{ request()->segment(2) }}">
                            <p class="mt-4">
                                <span>Hi,</span> <span class="fw-bold">{{ $user->email }}</span>
                            </p>
                            <p class="mt-3">
                                Verify your account by clicking the button below.
                                Thank you for registering!
                            </p>

                            <div class="form-group mt-3">
                                <button class="btn btn-primary" type="button" id="verifyBtn">Verify Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/auth/verify-account.js') }}"></script>
</body>

</html>
