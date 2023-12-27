<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register</title>
    @include('partials.header')
</head>

<body>
    @include('partials.navbar')

    <div class="container p-5">
        <div class="row">
            <div class="col-md-6 mx-auto" style="margin-bottom: 2rem;">
                <div class="card">
                    <div class="card-body">
                        <h1 class="mb-3">Register Here</h1>
                        <div id="showMessage"></div>
                        <form id="registerForm">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="name" class="form-control" name="name"
                                    placeholder="Full Name" value="{{ old('name') }}">
                            </div>

                            <div class="form-group mt-3">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email"
                                    placeholder="Email" value="{{ old('email') }}">
                            </div>

                            <div class="form-group mt-3">
                                <label for="password">Password</label>
                                <input id="password" class="form-control" type="password" name="password"
                                    placeholder="Password">
                            </div>

                            <div class="form-group mt-3">
                                <label for="password">Confirm Password</label>
                                <input id="confirm_password" class="form-control" type="password"
                                    name="confirm_password" placeholder="Confirm Password">
                                <span id="confirmPasswordValidationText"></span>
                            </div>

                            <div class="form-group mt-3">
                                <button class="btn btn-primary" type="button" id="registerBtn">Register</button>
                            </div>
                        </form>

                        <p class="mt-3">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/auth/register.js') }}" data-register="{{ route('register.process') }}"></script>
</body>

</html>
