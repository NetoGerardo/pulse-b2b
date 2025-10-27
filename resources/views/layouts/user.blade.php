<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Corretor </title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/pulse-round.png') }}">

    <link href="{{ asset('/assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />

    <link href="{{ asset('/dist/css/style.min.css') }}" rel="stylesheet">


    <!-- NEW Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-free-6.7.0-web/css/all.min.css') }}">

    <style>
        .btn {
            border-radius: 5px
        }

        .btn-success,
        .btn-secondary,
        .btn-danger,
        .btn-primary,
        .btn-info {
            color: white !important;
        }

        .current-page {
            /*background-color: #0b5ed7 !important;*/
            background-color: #5f76e8;
            color: white
        }
    </style>

</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <header class="topbar" data-navbarbg="skin6">
            <nav class="navbar top-navbar navbar-expand-md">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <div class="navbar-brand">
                        <a href="index.html">
                            <b class="logo-icon">
                            </b>
                            <span class="logo-text">
                                <img src="{{ asset('assets/images/pulse.png') }}" style="max-width:150px; padding-top: 20px" alt="homepage" class="dark-logo" />
                            </span>
                        </a>
                    </div>
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                        <li class="nav-item dropdown">
                        </li>
                    </ul>

                    <ul class="navbar-nav float-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-feather="settings" class="svg-icon"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <a class="dropdown-item" href="javascript:void(0)" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <div class="dropdown-divider"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        @include('components.user_backoffice')

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Bem vindo!</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    @yield('page-name')
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div id="app">
                    @yield('content')
                </div>
            </div>

            <footer class="footer text-center text-muted">
                All Rights Reserved.
            </footer>
        </div>
    </div>

    <!-- NECESSÁRIO PARA RENDERIZAR OS COMPONENTES VUE JS -->
    <script type="text/javascript" src="{{ asset('/js/app.js') }}"></script>

    <script src="{{ asset('/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="{{ asset('/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('/dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('/dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('/dist/js/custom.min.js') }}"></script>
    <!--This page JavaScript -->
    <script src="{{ asset('/assets/extra-libs/c3/d3.min.js') }}"></script>
    <script src="{{ asset('/assets/extra-libs/c3/c3.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('/assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('/dist/js/pages/dashboards/dashboard1.min.js') }}"></script>


    <!--Export Excel -->
    <script src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.min.js"></script>

</body>

</html>