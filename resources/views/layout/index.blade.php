<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
	@yield('links')
</head>
<body>

    <nav class="d-flex col-md justify-content-between align-items-center sticky-top">
        <a href="/" id="brand">
            <div id="logocont" class="">
                <img src="{{ asset('img/main-logo.png') }}" alt="Logo mexico" id="mainlogo" class="">
            </div>
            <h1 class="protest-g">Meksiko</h1>
        </a>

        <ul class="nav nav-underline fs-2 align-items-center me-5">
            <li class="nav-item mx-3">
                <a class="nav-link nunito {{ ($page === 'home') ? 'active' : '' }}" href="/" id="home">HOME</a>
            </li>
            <li class="nav-item mx-3">
                <a class="nav-link nunito {{ ($page === 'items') ? 'active' : '' }}" href="/items" id="items">ITEMS</a>
            </li>
            <li class="nav-item mx-3">
                <a class="nav-link nunito {{ ($page === 'categories') ? 'active' : '' }}" href="/categories" id="items">CATEGORIES</a>
            </li>
            <li class="nav-item mx-3">
                <a class="nav-link nunito {{ ($page === 'faktur') ? 'active' : '' }}" href="/faktur" id="faktur">FACTURE</a>
            </li>
			@can('is_admin')
            <li class="nav-item mx-3">
                <a class="nav-link nunito {{ ($page === 'manage') ? 'active' : '' }}" href="/manage" id="manage">MANAGE</a>
            </li>
			@endcan
            @auth
            <li class="nav-item me-2 ms-1" >
                <form action="/logout" method="POST">
                    @csrf
                    <button class="nav-link nunito" id="logins" type="submit"><i class="bi bi-box-arrow-left me-3"></i>LOGOUT</button>
                </form>
            </li>
            @else
            <li class="nav-item me-2 ms-1" >
                <a class="nav-link nunito" href="/login" id="logins"><i class="bi bi-box-arrow-in-right me-3"></i>LOGIN</a>
            </li>
            @endauth
        </ul>
    </nav>

    @yield('container')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
