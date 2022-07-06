@section('navigation')
    <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar fixed-top py-lg-0">
        <div class="container-fluid navbar-inner">
            <a href="/" class="navbar-brand ms-2">
                <small class="ms-1 font-weight-bold">
                    @if (request()->input('_academic'))
                        <span class="text-muted">Academic Year :</span>
                    @else
                        <span class="text-muted">Current School Year :</span>
                    @endif

                    <b>{{ Auth::user()->staff->current_academic()->semester }} |
                        {{ Auth::user()->staff->current_academic()->school_year }}</b>
                </small>
            </a>


            <div class="sidebar-toggle " data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20px" height="20px" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                    </svg>
                </i>
            </div>
            <div class=""style="margin-left:18%">
                <div class="dropdown mt-3 mb-2 w-100">
                    <a class=" dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        @if (request()->input('_academic'))
                            <span class="text-muted">Academic Year :</span>
                        @else
                            <span class="text-muted">Current School Year :</span>
                        @endif
                        <span class="text-primary h4 fw-bolder">{{ Auth::user()->staff->current_academic()->semester }} |
                            {{ Auth::user()->staff->current_academic()->school_year }}</span>
                    </a>
                    <ul class="dropdown-menu w-100" data-popper-placement="bottom-start">
                        @if (Auth::user()->staff->academics()->count() > 0)
                            @foreach (Auth::user()->staff->academics() as $_academic)
                                <li>
                                    <a class="dropdown-item "
                                        href="{{ Auth::user()->staff->navigation_dropdown_url() }}?_academic={{ base64_encode($_academic->id) }} {{ request()->is('accounting/particular/fee*') ? '&_department=' . request()->input('_department') : '' }}">
                                        {{ $_academic->semester }} | {{ $_academic->school_year }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
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
                    <li class="nav-item me-5">

                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('ticket.view') }}">
                            <svg width="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.24512 14.7815L10.2383 10.8914L13.6524 13.5733L16.5815 9.79297"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <circle cx="19.9954" cy="4.20027" r="1.9222" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                                <path
                                    d="M14.9248 3.12012H7.65704C4.6456 3.12012 2.77832 5.25284 2.77832 8.26428V16.3467C2.77832 19.3581 4.60898 21.4817 7.65704 21.4817H16.2612C19.2726 21.4817 21.1399 19.3581 21.1399 16.3467V9.30776"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg> <small>Ticket Concern</small></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">
                            <svg width="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.24512 14.7815L10.2383 10.8914L13.6524 13.5733L16.5815 9.79297"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <circle cx="19.9954" cy="4.20027" r="1.9222" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                                <path
                                    d="M14.9248 3.12012H7.65704C4.6456 3.12012 2.77832 5.25284 2.77832 8.26428V16.3467C2.77832 19.3581 4.60898 21.4817 7.65704 21.4817H16.2612C19.2726 21.4817 21.1399 19.3581 21.1399 16.3467V9.30776"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg> <small>Message</small></a>
                    </li>


                    <li>
                        <ul class="m-0 d-flex align-items-center navbar-list list-unstyled px-3 px-md-0">

                            <li>
                                <ul class="m-0 d-flex align-items-center navbar-list list-unstyled px-3 px-md-0">
                                    <li class="dropdown w-100">
                                        <a class="nav-link py-0 d-flex align-items-center" href="#"
                                            id="navbarDropdown3" role="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <img src="{{ asset(Auth::user()->staff->profile_pic(Auth::user()->staff)) }}"
                                                alt="User-Profile" class="img-fluid avatar avatar-50 avatar-rounded me-2">
                                            {{-- {{ Auth::user()->name }} --}}
                                        </a>
                                        <ul class="dropdown-menu  dropdown-menu-lg-end" aria-labelledby="navbarDropdown3">
                                            <li><a class="dropdown-item" href="{{ route('employee.profile') }}">My
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
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection
@section('side-navigation')
    <aside class="sidebar sidebar-default navs-rounded-all fixed-top">
        <div class="sidebar-header d-flex align-items-center justify-content-center">
            <a href="/" class="navbar-brand">
                <img src="{{ asset('assets/image/bma-logo-1.png') }}" class="avatar-30" alt="main_logo">
                <span class="ms-1 font-weight-bold"><b>oneBMA</b></span>
            </a>
            <div class="sidebar-toggle d-xl-none" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0 data-scrollbar">
            <div class="sidebar-list">

                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                    @foreach (Auth::user()->staff->side_bar_items() as $_item)
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
                                                            <circle cx="12" cy="12" r="8"
                                                                fill="currentColor"></circle>
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
                                                <a class="nav-link {{ request()->is(str_replace('http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/', '', strtolower(route($_route[1]))) . '*') ? 'active' : '' }}"
                                                    href="{{ route($_route[1]) }}">

                                                    <i class="icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="10"
                                                            viewBox="0 0 24 24" fill="currentColor">
                                                            <g>
                                                                <circle cx="12" cy="12" r="8"
                                                                    fill="currentColor"></circle>
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
                @if (Auth::user()->staff->academics()->count() > 0)
                    @foreach (Auth::user()->staff->academics() as $_academic)
                        <li>
                            <a class="dropdown-item "
                                href="{{ Auth::user()->staff->navigation_dropdown_url() }}?_academic={{ base64_encode($_academic->id) }} {{ request()->is('accounting/particular/fee*') ? '&_department=' . request()->input('_department') : '' }}">
                                {{ $_academic->semester }} | {{ $_academic->school_year }}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </nav>
@endsection
