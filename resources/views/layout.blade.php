<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="zaidysf">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ asset('img/logo.jpg') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="{{ asset('css/app-before.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        #title-right {
            opacity: 1;
            transition: opacity 1s;
        }

        #title-right.hide {
            opacity: 0;
        }
    </style>
    @yield('style')
</head>

<body>
    <div class="wrapper">
        @if (!Request::is('mini/*'))
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="{{ url('/') }}">
                    <span class="align-middle">{{ config('app.name') }}</span>
                </a>

                <ul class="sidebar-nav">

                    <li class="sidebar-header">
                        Master Data
                    </li>

                    <li class="sidebar-item {{ (Request::is('item_types') || Request::is('item_types/*') ? 'active' : '') }}">
                        <a class="sidebar-link" href="{{ route('item_types.index') }}">
                            <i class="align-middle" data-feather="sliders"></i> <span
                                class="align-middle">Item Types</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ (Request::is('items') || Request::is('items/*') ? 'active' : '') }}">
                        <a class="sidebar-link" href="{{ route('items.index') }}">
                            <i class="align-middle" data-feather="sliders"></i> <span
                                class="align-middle">Items</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ (Request::is('clients') || Request::is('clients/*') ? 'active' : '') }}">
                        <a class="sidebar-link" href="{{ route('clients.index') }}">
                            <i class="align-middle" data-feather="sliders"></i> <span
                                class="align-middle">Clients</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        Report
                    </li>

                    <li class="sidebar-item {{ (Request::is('invoices') || Request::is('invoices/*') ? 'active' : '') }}">
                        <a class="sidebar-link" href="{{ route('invoices.index') }}">
                            <i class="align-middle" data-feather="sliders"></i> <span
                                class="align-middle">Invoices</span>
                        </a>
                    </li>

                </ul>
            </div>
        </nav>
        @endif

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <h3 id="title-right" class="pt-2 hide">{{ config('app.name') }}</h3>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#"
                                data-bs-toggle="dropdown">
                                <span class="text-dark">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </a>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>

            @if (!Request::is('mini/*'))
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="#">
                                    <strong>{{ config('app.name') }}</strong></a> &copy;
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
            @endif
        </div>
    </div>

    <script src="{{ asset('js/app-before.js') }}"></script>
    <script>
        var elems = document.querySelectorAll('.sidebar-toggle');
        elems[0].addEventListener('click', fnn, false);
        function fnn() {
            var element = document.getElementById('sidebar');
            var title = document.getElementById('title-right');
            if (!element.classList.contains('collapsed')) {
                if(title.classList.contains("hide")){
                    title.classList.remove("hide");
                }
            } else {
                if(!title.classList.contains("hide")){
                    title.classList.add("hide");
                }
            }
        }
    </script>
    @yield('script')

</body>

</html>
