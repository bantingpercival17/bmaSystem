<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <title>@yield('page-title') | Baliwag Maritime Academy Inc.</title>


    <link rel="stylesheet" href="{{ asset('css/app-1.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>

<body>
    <!-- loader Start -->
    <div id="loading">
        <div class="loader ">
            <div class="loader-body word-spacing">
                <h1 class="loader-title fw-bold">BMA PORTAL</h1>
            </div>
        </div>
    </div>
    <!-- loader END -->
    <main class="main-content">
        <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar py-lg-0">
            <div class="container-fluid navbar-inner">
                <a href="./" class="navbar-brand text-primary">
                    <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2 12C2 6.48 6.49 2 12 2L12.2798 2.00384C17.6706 2.15216 22 6.57356 22 12C22 17.51 17.52 22 12 22C6.49 22 2 17.51 2 12ZM13.98 16C14.27 15.7 14.27 15.23 13.97 14.94L11.02 12L13.97 9.06C14.27 8.77 14.27 8.29 13.98 8C13.68 7.7 13.21 7.7 12.92 8L9.43 11.47C9.29 11.61 9.21 11.8 9.21 12C9.21 12.2 9.29 12.39 9.43 12.53L12.92 16C13.06 16.15 13.25 16.22 13.44 16.22C13.64 16.22 13.83 16.15 13.98 16Z"
                            fill="currentColor"></path>
                    </svg>
                </a>
                <a class="navbar-brand">
                    <h2 class="logo-title ">{{ $_subject->curriculum_subject->subject->subject_code }}</h2>
                </a>
                <div>
                    <p class="text-primary mt-2 mb-0">
                        {{ $_subject->curriculum_subject->subject->subject_name }}</p>
                    <small>{{ strtoupper($_subject->staff->first_name . ' ' . $_subject->staff->last_name) }}</small>

                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
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
                                            <ul
                                                class="m-0 d-flex align-items-center navbar-list list-unstyled px-3 px-md-0">
                                                <li class="dropdown m-2">
                                                    <a class="nav-link py-0 d-flex align-items-center" href="#"
                                                        id="navbarDropdown3" role="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <img src="{{ asset(Auth::user()->student->profile_pic(Auth::user())) }}"
                                                            alt="User-Profile"
                                                            class="img-fluid avatar avatar-50 avatar-rounded me-2">
                                                        {{ str_replace('@bma.edu.ph', '', Auth::user()->campus_email) }}
                                                    </a>
                                                    <ul class="dropdown-menu  dropdown-menu-lg-end"
                                                        aria-labelledby="navbarDropdown3">
                                                        <li><a class="dropdown-item" href="{{ route('home') }}">My
                                                                Profile</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('student.accounts') }}">Accounts</a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('logout') }}" method="post">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="dropdown-item">Logout</button>
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


        <div class="conatiner-fluid content-inner mt-6 py-0">
            @yield('page-content')
        </div>
    </main>

    <!-- Library Bundle Script -->
    <script src="{{ asset('resources/js/core/libs.min.js') }}"></script>

    <!-- External Library Bundle Script -->
    <script src="{{ asset('resources/js/core/external.min.js') }}"></script>

    <!-- Widgetchart Script -->
    <script src="{{ asset('resources/js/charts/widgetcharts.js') }}"></script>

    <!-- mapchart Script -->
    <script src="{{ asset('resources/js/charts/vectore-chart.js') }}"></script>
    <script src="{{ asset('resources/js/charts/dashboard.js') }}" defer></script>

    <!-- fslightbox Script -->
    <script src="{{ asset('resources/js/plugins/fslightbox.js') }}"></script>

    <!-- GSAP Animation -->
    <script src="{{ asset('resources/vendor/gsap/gsap.min.js') }}"></script>
    <script src="{{ asset('resources/vendor/gsap/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('resources/js/gsap-init.js') }}"></script>

    <!-- Form Wizard Script -->
    <script src="{{ asset('resources/js/plugins/form-wizard.js') }}"></script>

    <!-- App Script -->
    <script src="{{ asset('resources/js/gigz.js') }}" defer></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
