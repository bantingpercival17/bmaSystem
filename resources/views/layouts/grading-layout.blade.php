<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <title>@yield('page-title') | Baliwag Maritime Academy Inc.</title>


    <link rel="stylesheet" href="{{ asset('css/app-1.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    {{-- <script src="{{ asset('js/app-1.js') }}"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script> --}}
    <style>
        body {
            font: 90%/1.4 system-ui;
            margin: 0;
            font-family: sans-serif;
        }

        header {
            padding: 7vh 5vw;
            border-bottom: 1px solid #ddd;
        }

        header h1,
        header p {
            margin: 0;
        }

        footer {
            padding: 7vh 5vw;
            border-top: 1px solid #ddd;
        }

        aside {
            padding: 7vh 5vw;
        }

        .primary {
            overflow: auto;
            scroll-snap-type: both mandatory;
            height: 80vh;
        }

        @media (min-width: 40em) {
            main {
                display: flex;
            }

            aside {
                flex: 0 1 20vw;
                order: 1;
                border-right: 1px solid #ddd;
            }

            .primary {
                order: 2;
            }
        }

        table {
            border-collapse: collapse;
            border: 0;
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

<body class=" ">

    @if (Auth::user())
        <main class="main-content">
            @if (request()->url('teacher/subject/*'))
                @yield('page-content')
            @else
                <div class="conatiner-fluid content-inner mt-6 py-0">
                    @yield('page-content')
                </div>
            @endif

        </main>
    @else
        @yield('page-content')
    @endif

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
    @yield('js')
</body>

</html>
