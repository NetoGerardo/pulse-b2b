    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a class="nav-link" style="font-size: 17px;">Bem vindo!</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="fa fa-cog"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="navbarDropdown">

                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-solid fa-user mr-2"></i> Minha conta
                </a>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fa fa-solid fa-share mr-2"></i> {{ __('Sair') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

                <div class="dropdown-divider"></div>
                <a href="https://whatsz.com.br" target="_blank" class="dropdown-item dropdown-footer">whatz.com.br</a>
            </div>
        </ul>

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/admin" class="brand-link">
            <img src="{{ asset('/dist/img/newicon.png') }}" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light"><b>Whats</b>Z</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                    <li class="nav-item menu-open">
                        <a href="/admin" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/users/list" class="nav-link">
                            <i class="nav-icon fas fa-solid fa-hammer"></i>
                            <p>
                                Usuários
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/connection" class="nav-link">
                            <i class="nav-icon fa fa-solid fa-wifi"></i>
                            <p>
                                Conexão
                                <span class="right badge badge-danger">!</span>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon far fa-envelope "></i>
                            <p>
                                Envios
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/send/create/texto" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Texto</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/send/create/imagem" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Imagem</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/send/create/video" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Vídeo</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/send/create/link" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Link</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/send/create/audio" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Áudio</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-header">AUTOMAÇÃO</li>
                    <li class="nav-item">
                        <a href="/sequence/create" class="nav-link">
                            <i class="nav-icon fas fa-filter"></i>
                            <p>
                                Sequência
                                <span class="right badge badge-danger">New</span>
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">ESTATÍSTICAS</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Relatórios
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/send/list" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Envios</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>