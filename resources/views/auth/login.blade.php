<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    @include('partials.header')
</head>

<body>
    @include('partials.navbar')

    <div class="container p-5">
        <div class="row">
            <div class="col-md-6 mx-auto" style="margin-bottom: 2rem;">
                <div class="card">
                    <div class="card-body">
                        <h1 class="mb-3">Login Here</h1>
                        <div id="showMessage"></div>
                        <div id="tooManyAttemptsMessage"></div>
                        <form id="loginForm">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email"
                                    placeholder="Valid Email" value="{{ old('email') }}">
                            </div>

                            <div class="form-group mt-3">
                                <label for="password">Password</label>
                                <input id="password" class="form-control" type="password" name="password"
                                    placeholder="Valid Password">
                            </div>

                            <div class="form-group mt-3">
                                <button class="btn btn-primary" type="button" id="loginBtn">Login</button>
                            </div>
                        </form>

                        <p class="mt-3">Don't have an account? <a href="{{ route('register') }}"
                                style="display:inline; margin-left: 5px;">Register here</a></p>
                        <p class="mt-3">Forgot your password? <a href="{{ route('password.request') }}"
                                style="display:inline; margin-left: 5px;">Reset here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/auth/login.js') }}" data-login="{{ route('login.process') }}"></script>
</body>

</html>
