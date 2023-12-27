<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Provide New Password</title>
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
                        <h1 class="mb-3">Provide New Password</h1>
                        <div id="showMessage"></div>
                        <form id="resetPasswordForm">
                            <input type="hidden" name="token" value="{{ request()->segment(2) }}">
                            <div class="form-group">
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
                                <button class="btn btn-primary" type="button" id="resetPasswordBtn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/auth/change-password.js') }}"></script>
</body>

</html>
