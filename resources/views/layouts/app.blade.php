<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .input-group .form-control, .input-group .btn {
            height: auto;
        }
        .input-group {
            display: flex;
            align-items: stretch;
        }
        .navbar-toggler { /* Hamburger Icon to toggle the sidebar */
            z-index: 9999;
        }
    </style>
</head>

<body>
    <!-- Fixed Header -->
    <header class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <!-- Hamburger Icon (visible on small screens) to toggle the sidebar -->
            <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle" style="border:none;">
                <span class="material-symbols-outlined">menu</span>
            </button>

            <!-- Logo -->
            <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 2rem; color:#007bff">FEUPshare</a>

            <!-- Search Form -->
            <form class="form-inline mx-auto w-50 max-w-600" action="{{ route('search.questions') }}" method="GET">
                <div class="input-group w-100">
                    <input class="form-control" type="search" placeholder="Search questions..." aria-label="Search" name="query">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success d-flex align-items-center justify-content-center" type="submit">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- User Links -->
            <div class="d-flex align-items-center">
                @if (Auth::check())
                    <!-- Profile Icon -->
                    <a href="{{ route('users.show', Auth::id()) }}" class="btn btn-link p-0 mx-2" title="Profile">
                        <span class="material-symbols-outlined" style="font-size: 2rem;">person</span>
                    </a>
                    <!-- Notifications Icon -->
                    <a href="#" class="btn btn-link p-0 mx-2" title="Notifications">
                        <span class="material-symbols-outlined" style="font-size: 2rem;">notifications</span>
                    </a>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="d-inline-block ml-2">
                        @csrf
                        <button type="submit" class="btn btn-primary d-flex align-items-center">
                            <span class="material-symbols-outlined" style="font-size: 1.5rem; margin-right: 5px;">logout</span> Logout
                        </button>
                    </form>
                @else
                    <!-- Login and Register Links -->
                    <a class="btn btn-primary ml-2" href="{{ url('/login') }}">Login</a>
                    <a class="btn btn-secondary ml-2" href="{{ url('/register') }}">Register</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content with Sidebar -->
    <div class="container-fluid" style="padding-top: 70px; height: 100vh;">
        <div class="row h-100">
            <!-- Sidebar Column -->
            <div class="col-md-2 bg-light sidebar pb-3 vh-100">
                @include('partials.sidebar')
            </div>

            <!-- Main Content Column -->
            <div class="col-md-10">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>