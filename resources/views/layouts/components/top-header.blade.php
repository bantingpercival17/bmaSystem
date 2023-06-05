<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar fixed-top py-lg-0">
    <div class="container-fluid navbar-inner">
        <a href="/" class="navbar-brand ms-2">
            <small class="ms-1 font-weight-bold">
                @yield('page-title')
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
                    <div class="dropdown mt-3 mb-2 w-100">
                        <a class=" dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            @if (request()->input('_academic'))
                                <span class="text-muted">Academic Year :</span>
                            @else
                                <span class="text-muted">Current School Year :</span>
                            @endif
                            <span
                                class="text-primary h4 fw-bolder">{{ Auth::user()->staff->current_academic()->semester }}
                                |
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