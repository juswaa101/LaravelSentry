<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('landing.page') }}">Laragon</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01"
            aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto">
                @auth
                    @if (auth()->user()->is_verified || (auth()->user()->is_two_factor_enabled && auth()->user()->is_two_factor_verified))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Welcome, {{ auth()->user()->name }}</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Logged in as {{ auth()->user()->name }}</a>
                            @if (auth()->user()->is_verified)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('profile') }}">Account Settings</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" id="logoutBtn" style="cursor: pointer;">Logout</a>
                        </div>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@auth
    <script src="{{ asset('js/auth/logout.js') }}" data-logout="{{ route('logout') }}"></script>
@endauth
