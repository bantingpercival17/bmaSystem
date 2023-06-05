
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
                                                    href="{{ route($_route[1]) }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}">
    
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
