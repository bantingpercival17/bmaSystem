<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <title>@yield('page-title') | Baliwag Maritime Academy Inc.</title>


    <link rel="stylesheet" href="{{ asset('css/app-1.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
        .iframe-placeholder {
            background: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100% 100%"><text fill="%23FF0000" x="50%" y="50%" font-family="\'Lucida Grande\', sans-serif" font-size="24" text-anchor="middle">LOADING.....</text></svg>') 0px 0px no-repeat;
        }

    </style>
    @yield('css')
</head>

<body class=" ">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader ">
            <div class="loader-body word-spacing">
                <h1 class="loader-title fw-bold">BMA PORTAL</h1>
            </div>
        </div>
    </div>
    <!-- loader END -->

    @if (Auth::user())
        @include('layouts.navigation-main')
        @yield('side-navigation')
        <main class="main-content">
            @yield('navigation')
            @if (request()->is('teacher/subjects/*'))
                @yield('page-content')
            @else
                <div style="margin-top:7%;">
                    <ol class="breadcrumb">
                        @yield('beardcrumb-content')
                    </ol>
                    @foreach (Auth::user()->staff->routes_navigation() as $route)
                        @if (request()->is($route))
                            @yield('sub-navigation')
                        @endif
                    @endforeach

                    <div class="conatiner-fluid content-inner mt-5 py-0">
                        @yield('page-content')
                    </div>
                </div>

            @endif

        </main>
    @else
        @yield('page-content')
    @endif

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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('resources\plugin\select\js\select2.min.js') }}">
    </script>

    <script>
        $('.select').select2()
        @if (Session::has('success'))
            Swal.fire({
            title: 'Complete!',
            text:"{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Okay'
            })
            /* toastr.success("{{ session('message') }}") */
        @endif
        @if (Session::has('error'))
            Swal.fire({
            title: 'Existing Data!',
            text:"{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'Okay'
            })
            /* toastr.success("{{ session('message') }}") */
        @endif
        var message = "<?php echo session('reset-password'); ?>"
        @if (Session::has('reset-password'))
            Swal.fire({
            title: 'Complete!',
            text:/* "{{ session('reset-password') }}" */ message,
            icon: 'success',
            confirmButtonText: 'Okay'
            })
        @endif
        $('.input-select').click(function() {
            var data = $(this).data('check')
            $('.input-select-' + data).prop('checked', $(this).prop('checked'))
            //alert(data)
        })
        $('.form-check-input').click(function() {
            var data = $(this).prop('checked')
            if (data == false) {
                var categ = $(this).data('category'),
                    content = $(this).data('content'),
                    id = $(this).val();
                if ((categ) && (content) && id) {
                    $.get('uncleared?category=' + categ + "&content=" + content + "&id=" + id, function(data) {
                        if (data.data.respond == 200) {
                            // Message Notication
                        }
                        console.log(data)

                    }).fail(function() {
                        console.info('Error')
                    })
                }
            }
            if (data == true) {
                var categ = $(this).data('category'),
                    content = $(this).data('content'),
                    id = $(this).val();
                if (categ == 'academic') {
                    $.get('cleared?category=' + categ + "&content=" + content + "&id=" + id, function(data) {
                        if (data.data.respond == 200) {
                            // Message Notication
                        }
                        console.log(data)

                    }).fail(function() {
                        console.info('Error')
                    })
                }
            }
        })
    </script>
    @yield('js')
</body>

</html>
