<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>
    @include('partials.header')
</head>

<body>
    @include('partials.navbar')

    <div class="container mt-5">
        @if (auth()->check())
            <!-- Content for authenticated users -->
            <div class="jumbotron">
                <h1 class="display-4">Welcome, {{ auth()->user()->name }}!</h1>
                <hr class="my-4">
                <p class="lead">We're glad to have you here. Explore and enjoy our exclusive content.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Browse More</a>
            </div>
        @else
            <!-- Content for guest users -->
            <div class="jumbotron">
                <h1 class="display-4">Welcome, Guest!</h1>
                <p class="lead">Explore our platform and discover amazing features. Log in to access even more!</p>
                <p class="lead">
                    <a class="btn btn-primary" href="{{ route('login') }}" role="button">Log In</a>
                </p>
                <hr class="my-4">
                <p>If you don't have an account yet, <a href="{{ route('register') }}">register here</a>.</p>
            </div>
        @endif
    </div>

    @include('partials.footer')
</body>

</html>
