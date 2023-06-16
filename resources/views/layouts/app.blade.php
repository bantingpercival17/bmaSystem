<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <title>@yield('page-title') | Baliwag Maritime Academy Inc.</title>

    <link rel="stylesheet" href="{{ asset('css/app-1.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('resources/plugin/toastify/toastify.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class=" ">
    <div id="loading">
        <div class="loader">
            <div class="loader-body word-spacing">
                <h1 class="loader-title fw-bold">BMA PORTAL</h1>
            </div>
        </div>
    </div>
    @if (Auth::user())
    @livewire('components.side-navigation-menu')
    <main class="main-content">
        <div class="position-relative">
            @livewire('components.top-navigation-menu')
        </div>
        <div style="margin-top:7%;">

            <div class="conatiner-fluid content-inner">


                {{ $slot }}
            </div>
        </div>
    </main>
    @else
    {{ $slot }}
    @endif
    @livewireScripts
    {{-- <script src="{{ asset('js/app-1.js') }}"></script> --}}
    <script src="{{ asset('resources/js/core/libs.min.js') }}"></script><!-- Library Bundle Script -->
    <script src="{{ asset('resources/js/core/external.min.js') }}"></script> <!-- External Library Bundle Script -->
    <script src="{{ asset('resources/js/charts/widgetcharts.js') }}"></script><!-- Widgetchart Script -->
    <script src="{{ asset('resources/js/charts/vectore-chart.js') }}"></script><!-- mapchart Script -->
    <script src="{{ asset('resources/js/charts/dashboard.js') }}" defer></script>
    <script src="{{ asset('resources/js/plugins/fslightbox.js') }}"></script> <!-- fslightbox Script -->

    <!-- GSAP Animation -->
    <script src="{{ asset('resources/vendor/gsap/gsap.min.js') }}"></script>
    <script src="{{ asset('resources/vendor/gsap/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('resources/js/gsap-init.js') }}"></script>

    <!-- Form Wizard Script -->
    <script src="{{ asset('resources/js/plugins/form-wizard.js') }}"></script>

    <!-- App Script -->
    <script src="{{ asset('resources/js/gigz.js') }}" defer></script>
    {{-- documents Viewr --}}
    <script src="{{ asset('resources/js/plugins/custom-document-viewer.js') }}"></script>
    <script src="{{ asset('resources/js/plugins/viewer.1.0.0.js') }}"></script>
    <!--  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="{{ asset('resources/plugin/select/js/select2.min.js') }}"></script> -->
    <script src="{{ asset('resources/plugin/toastify/toastify.js') }}"></script>
    <script src="{{ asset('resources/plugin/editor/editor.js') }}"></script>
    @yield('script')
</body>

</html>