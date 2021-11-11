@section('page-nav-bar')
    <nav class="main-header navbar navbar-expand navbar-dark">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user mr-2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user-circle mr-2"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                this.closest('form').submit();"> <i
                                class="fas fa-sign-out-alt mr-2"></i>
                            Sign
                            Out</a>

                    </form>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('page-main-side')
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img src="{{ asset('assets/img/bma-logo.jpg') }}" alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">oneBMA</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                @php
                    if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', Auth::user()->name)) . '.jpg'))) {
                        $_image = strtolower(str_replace(' ', '_', Auth::user()->name)) . '.jpg';
                    } else {
                        $_image = 'avatar.png';
                    }
                @endphp
                <div class="image">
                    <img src="{{ asset('/assets/img/staff/' . $_image) }}" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}
                        <br>
                        <span class="text-muted">{{ Auth::user()->staff->job_description }}</span>
                    </a>
                </div>
            </div>

            <nav class="mt-2">
                @include('widgets.page_sidebar')
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    @if (Auth::user()->roles[0]['id'] == 1) {{-- Super Admin --}}
                        @yield('administrator-side')
                    @endif
                    @if (Auth::user()->roles[0]['id'] == 2)
                        @yield('administrator-side')
                    @endif
                    @if (Auth::user()->roles[0]['id'] == 3) {{-- Registrar Sidebar --}}
                        @yield('registrar-side')
                    @endif
                    @if (Auth::user()->roles[0]['id'] == 4) {{-- Accounting Sidebar --}}
                        @yield('accounting-side')
                    @endif
                    @if (Auth::user()->roles[0]['id'] == 6) {{-- Teacher Sidebar --}}
                        @yield('teacher-side')
                    @endif
                    @if (Auth::user()->roles[0]['id'] == 8) {{-- Executive Sidebar --}}
                        @yield('executive-side')
                    @endif
                </ul>
            </nav>
        </div>
    </aside>
@endsection
