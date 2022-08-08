<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Task</title>
    @livewireStyles
</head>

<body>

    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">













                    @if (Route::has('login'))
                        @auth
                            <li class="setting__bar__icon"><a class="setting__active" href="#"></a>
                                <div class="searchbar__content setting__block">
                                    <div class="content-inner">





                                        <div class="switcher-currency-trigger">
                                            <span class="currency-trigger">
                                                <a class="text-decoration-none"
                                                    href="{{ route('logout') }}"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    Logout
                                                </a>
                                            </span>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>
                                        </div>


                                    </div>
                                </div>

                            </li>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log
                                in</a>
                            <a href="{{ route('register') }}"
                                class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>

                    @endauth
                    @endif











                </div>
            </div>
        </nav>

        @yield('content')

    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('script')

    @livewireScripts
</body>

</html>
