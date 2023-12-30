<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Request Password Reset</title>
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
                        <h1 class="mb-3">Request Password Reset</h1>
                        <div id="showMessage"></div>
                        <div id="tooManyAttemptsMessage"></div>
                        <form id="sendPasswordResetForm">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email"
                                    placeholder="Valid Email" value="{{ old('email') }}">
                            </div>

                            <div class="form-group mt-3">
                                <button class="btn btn-primary" type="button"
                                    id="sendPasswordResetBtn">Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/auth/forgot-password.js') }}"></script>
</body>

</html>
