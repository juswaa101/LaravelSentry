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

    <div class="container mt-4" style="margin-bottom:5rem !important;">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Login</h1>
                        <div id="showMessage"></div>
                        <div id="tooManyAttemptsMessage"></div>
                        <form id="loginForm">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email"
                                    placeholder="Enter your email" value="{{ old('email') }}" required>
                            </div>

                            <div class="form-group mt-3">
                                <label for="password">Password</label>
                                <input id="password" class="form-control" type="password" name="password"
                                    placeholder="Enter your password" required>
                            </div>

                            <div class="form-group mt-3">
                                <button class="btn btn-primary btn-block" type="button" id="loginBtn">Login</button>
                            </div>
                        </form>

                        <hr>

                        <div class="text-center">
                            <p class="mb-2">Don't have an account? <a href="{{ route('register') }}">Register here</a>
                            </p>
                            <p class="mb-4">Forgot your password? <a href="{{ route('password.request') }}">Reset
                                    here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('partials.footer')

    <script src="{{ asset('js/auth/login.js') }}" data-login="{{ route('login.process') }}"></script>
</body>

</html>
