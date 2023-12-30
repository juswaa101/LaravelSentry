<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login with Two Factor</title>

    @include('partials.header')
</head>

<body>
    @include('partials.navbar')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Two Factor Authentication</div>

                    <div class="card-body">
                        <div id="showMessage"></div>
                        <form id="twoFactorAuthForm">
                            <div class="form-group">
                                <label for="code">Authentication Code</label>
                                <input id="code" type="text" class="form-control" name="code"
                                    placeholder="XXXX-XXXX-XXXX-XXXX-XXXX">
                            </div>

                            <div class="form-group mt-3">
                                <button type="button" class="btn btn-primary" id="verifyBtn">
                                    Verify
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/auth/two-factor-authenticate.js') }}"></script>
</body>

</html>
