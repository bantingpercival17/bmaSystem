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

    <style>
        .primary {
            overflow: auto;
            scroll-snap-type: x mandatory;
            height: 100vh;
        }

        @media (min-width: 40em) {
            .maint-table {
                display: flex;
            }

            .primary {
                order: 2;
            }
        }

        table {
            border-collapse: collapse;
            border: 1px solid #aaa;
        }

        th,
        td {
            border: 1px solid #aaa;
            background-clip: padding-box;
            scroll-snap-align: start;
        }

        tbody tr:last-child th,
        tbody tr:last-child td {
            border-bottom: 0;
        }

        thead {
            z-index: 1000;
            position: relative;
        }

        th,
        td {
            padding: 0.6rem;
            min-width: 6rem;
            text-align: left;
            margin: 0;
        }

        thead th {
            position: sticky;
            top: 0;
            border-top: 0;
            background-clip: padding-box;
        }

        thead th.pin {
            left: 0;
            z-index: 1001;
            border-left: 0;
        }

        tbody th {
            background-clip: padding-box;
            border-left: 0;
        }

        tbody {
            z-index: 10;
            position: relative;
        }

        tbody th {
            position: sticky;
            left: 0;
        }

        thead th,
        tbody th {
            background-color: #f8f8f8;
        }
    </style>
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
                    <h2 class="logo-title ">GRADING SHEET</h2>
                </a>
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
                                                        <img src="{{ asset(Auth::user()->staff->profile_pic(Auth::user()->staff)) }}"
                                                            alt="User-Profile"
                                                            class="img-fluid avatar avatar-50 avatar-rounded me-2">
                                                        {{ Auth::user()->name }}
                                                    </a>
                                                    <ul class="dropdown-menu  dropdown-menu-lg-end"
                                                        aria-labelledby="navbarDropdown3">

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

        <div class="grading-layout">
            @yield('page-content')
        </div>
        {{-- <div class="conatiner-fluid content-inner">
            @yield('page-content')
        </div> --}}
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
    <script>
        @if (Session::has('success'))
            Swal.fire({
                title: 'Complete!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Okay'
            })
            /* toastr.success("{{ session('message') }}") */
        @endif
        @if (Session::has('error'))
            Swal.fire({
                title: 'Existing Data!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'Okay'
            })
            /* toastr.success("{{ session('message') }}") */
        @endif
    </script>
    @yield('js')
</body>

</html>
