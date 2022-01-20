@section('navigation')
    <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar py-lg-0 nav-fixed-top">
        <div class="container-fluid navbar-inner ">
            <a href="/" class="navbar-brand ms-2">
                <span class="ms-1 font-weight-bold"><b>@yield('page-title')</b></span>
            </a>
            <div class="sidebar-toggle " data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20px" height="20px" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                    </svg>
                </i>
            </div>
            <div class="input-group search-input">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mt-2">
                        @yield('beardcrumb-content')
                    </ol>
                </nav>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                    <span class="navbar-toggler-bar bar1 mt-2"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto top-menu navbar-nav align-items-center navbar-list mb-3 mb-lg-0">
                    <li>
                        <ul class="m-0 d-flex align-items-center navbar-list list-unstyled px-3 px-md-0">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul
                                    class="navbar-nav ms-auto top-menu navbar-nav align-items-center navbar-list mb-3 mb-lg-0">

                                    <li>
                                        <ul class="m-0 d-flex align-items-center navbar-list list-unstyled px-3 px-md-0">
                                            <li class="dropdown">
                                                <a class="nav-link py-0 d-flex align-items-center" href="#"
                                                    id="navbarDropdown3" role="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <img src="{{ asset(Auth::user()->staff->profile_pic(Auth::user()->staff)) }}"
                                                        alt="User-Profile"
                                                        class="img-fluid avatar avatar-50 avatar-rounded me-2">
                                                    {{ Auth::user()->name }}
                                                </a>
                                                <ul class="dropdown-menu  dropdown-menu-lg-end"
                                                    aria-labelledby="navbarDropdown3">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('employee.profile') }}">My
                                                            Profile</a></li>

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('logout') }}" method="post">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">Logout</button>
                                                        </form>

                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>


                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
@endsection
@section('side-navigation')
    <aside class="sidebar sidebar-default navs-rounded-all ">
        <div class="sidebar-header d-flex align-items-center justify-content-center">
            <a href="/" class="navbar-brand">
                <img src="{{ asset('assets/image/bma-logo-1.png') }}" class="avatar-30" alt="main_logo">
                <span class="ms-1 font-weight-bold"><b>oneBMA</b></span>
            </a>
            <div class="sidebar-toggle d-xl-none" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0 data-scrollbar">
            <div class="sidebar-list">
                @php
                    $_side_nav = [
                        [
                            'role_id' => 0,
                            'role_name' => 'Employee',
                            'role_icon' => 'icon-user',
                            'role_routes' => [['Attendance', 'employee.attendance'], ['Profile', 'employee.attendance']],
                        ],
                        [
                            'role_id' => 1,
                            'role_name' => 'Administrator',
                            'role_icon' => 'icon-job',
                            'role_routes' => [['Dashboard', 'admin.dashboard'], ['Enrollment', 'admin.enrollment'], ['Students', 'admin.students'], ['Accounts', 'admin.accounts'], ['Attendance', 'admin.attendance'], ['Subjects', 'admin.subjects'], ['Section', 'admin.sections']],
                        ],
                        [
                            'role_id' => 2,
                            'role_name' => 'Administrative',
                            'role_icon' => 'icon-job',
                            'role_routes' => [['Dashboard', 'administrative.dashboard'], ['Attendace', 'administrative.attendance'], ['Employees', 'administrative.employees']],
                        ],
                        [
                            'role_id' => 3,
                            'role_name' => 'Registrar',
                            'role_icon' => 'icon-job',
                            'role_routes' => [['Dashboard', 'registrar.dashboard'], ['Enrollment', 'registrar.enrollment'], ['Students', 'registrar.students']],
                        ],
                        [
                            'role_id' => 4,
                            'role_name' => 'Accounting',
                            'role_icon' => 'icon-job',
                            'role_routes' => [['Dashboard', 'administrative.dashboard'], ['Enrollment', 'admin.enrollment']],
                        ],
                        [
                            'role_id' => 5,
                            'role_name' => 'Onboard Training',
                            'role_icon' => 'icon-job',
                            'role_routes' => [['Dashboard', 'onboard.dashboard'], ['Midshipman', 'onboard.midshipman'], ['Shipboard', 'onboard.shipboard']],
                        ],
                        [
                            'role_id' => 6,
                            'role_name' => 'Teacher',
                            'role_icon' => 'icon-job',
                            'role_routes' => [['Subjects', 'teacher.subject-list'], ['Previous Subject', 'teacher.previous-subjects']],
                        ],
                        [
                            'role_id' => 6.5,
                            'role_name' => 'Department Head',
                            'role_icon' => 'icon-job',
                            'role_routes' => [['Grade Submission', 'department-head.grade-submission'], ['E-Clearance', 'department.e-clearance']],
                        ],
                        [
                            'role_id' => 7,
                            'role_name' => 'Maintenance',
                            'role_icon' => 'icon-job',
                            'role_routes' => [['Dashboard', 'administrative.dashboard'], ['Enrollment', 'admin.enrollment']],
                        ],
                        [
                            'role_id' => 8,
                            'role_name' => 'Executive',
                            'role_icon' => 'icon-job',
                            'role_routes' => [['Dashboard', 'administrative.dashboard'], ['Enrollment', 'admin.enrollment']],
                        ],
                    ];
                    
                @endphp
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">



                    @foreach ($_side_nav as $_item)
                        @if ($_item['role_id'] == 0)
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse"
                                    href="#{{ str_replace(' ', '-', strtolower($_item['role_name'])) }}" role="button"
                                    aria-expanded="false"
                                    aria-controls="{{ str_replace(' ', '-', strtolower($_item['role_name'])) }}">
                                    <i class="icon">
                                        @include('layouts.icon-main')
                                        @yield($_item['role_icon'])
                                    </i>
                                    <span class="item-name">{{ $_item['role_name'] }}</span>
                                    <i class="right-icon">

                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </i>
                                </a>
                                <ul class="sub-nav collapse"
                                    id="{{ str_replace(' ', '-', strtolower($_item['role_name'])) }}"
                                    data-bs-parent="#sidebar-menu">
                                    @foreach ($_item['role_routes'] as $_route)
                                        <li class="nav-item">
                                            <a class="nav-link {{ request()->routeIs($_route[1]) ? 'active' : '' }}"
                                                href="{{ route($_route[1]) }}">
                                                <i class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24"
                                                        fill="currentColor">
                                                        <g>
                                                            <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                                        </g>
                                                    </svg>
                                                </i>
                                                <i class="sidenav-mini-icon"> H </i>
                                                <span class="item-name">{{ $_route[0] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                        @foreach (Auth::user()->roles as $role)
                            @if ($role->id == 6)
                                @if ($_item['role_id'] == 6.5 && Auth::user()->email == 'k.j.cruz@bma.edu.ph')
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="collapse"
                                            href="#{{ str_replace(' ', '-', strtolower($_item['role_name'])) }}"
                                            role="button" aria-expanded="false"
                                            aria-controls="{{ str_replace(' ', '-', strtolower($_item['role_name'])) }}">
                                            <i class="icon">
                                                @include('layouts.icon-main')
                                                @yield($_item['role_icon'])
                                            </i>
                                            <span class="item-name">{{ $_item['role_name'] }}</span>
                                            <i class="right-icon">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </i>
                                        </a>
                                        <ul class="sub-nav collapse"
                                            id="{{ str_replace(' ', '-', strtolower($_item['role_name'])) }}"
                                            data-bs-parent="#sidebar-menu">
                                            @foreach ($_item['role_routes'] as $_route)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ request()->routeIs($_route[1]) ? 'active' : '' }}"
                                                        href="{{ route($_route[1]) }}">
                                                        <i class="icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="10"
                                                                viewBox="0 0 24 24" fill="currentColor">
                                                                <g>
                                                                    <circle cx="12" cy="12" r="8" fill="currentColor">
                                                                    </circle>
                                                                </g>
                                                            </svg>
                                                        </i>
                                                        <i class="sidenav-mini-icon"> H </i>
                                                        <span class="item-name">{{ $_route[0] }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif

                            @endif
                            @if ($_item['role_id'] == $role->id)
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="collapse"
                                        href="#{{ str_replace(' ', '-', strtolower($_item['role_name'])) }}"
                                        role="button" aria-expanded="false"
                                        aria-controls="{{ str_replace(' ', '-', strtolower($_item['role_name'])) }}">
                                        <i class="icon">
                                            @include('layouts.icon-main')
                                            @yield($_item['role_icon'])
                                        </i>
                                        <span class="item-name">{{ $_item['role_name'] }}</span>
                                        <i class="right-icon">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </i>
                                    </a>
                                    <ul class="sub-nav collapse"
                                        id="{{ str_replace(' ', '-', strtolower($_item['role_name'])) }}"
                                        data-bs-parent="#sidebar-menu">
                                        @foreach ($_item['role_routes'] as $_route)
                                            <li class="nav-item">
                                                <a class="nav-link {{ request()->routeIs($_route[1]) ? 'active' : '' }}"
                                                    href="{{ route($_route[1]) }}">
                                                    <i class="icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="10"
                                                            viewBox="0 0 24 24" fill="currentColor">
                                                            <g>
                                                                <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                                            </g>
                                                        </svg>
                                                    </i>
                                                    <i class="sidenav-mini-icon"> H </i>
                                                    <span class="item-name">{{ $_route[0] }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif


                        @endforeach
                    @endforeach

                </ul>

            </div>
            <div class="p-2 "></div>
        </div>


    </aside>
@endsection

@section('sub-navigation')
    <nav class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">
        <div class="dropdown mt-3 mb-2 w-100">
            <a class=" dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                aria-expanded="false">
                <span class="text-muted">Academic Year :</span>
                <b>{{ Auth::user()->staff->current_academic()->semester }} |
                    {{ Auth::user()->staff->current_academic()->school_year }}</b>
            </a>
            <ul class="dropdown-menu w-100" data-popper-placement="bottom-start">
                @php
                    $_url = route('registrar.enrollment');
                    /* $_url = request()->is('student/academic/grades') ? route('academic.grades') : $_url;
                     $_url = request()->is('student/academic/clearance') ? route('academic.clearance') : $_url; */
                @endphp
                @if (Auth::user()->staff->academics()->count() > 0)
                    @foreach (Auth::user()->staff->academics() as $_academic)

                        <li>
                            <a class="dropdown-item "
                                href="{{ $_url }}?_academic={{ base64_encode($_academic->id) }}">
                                {{ $_academic->semester }} | {{ $_academic->school_year }}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </nav>
@endsection
