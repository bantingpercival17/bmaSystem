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

    {{-- <script src="{{ asset('js/app-1.js') }}"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script> --}}

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
            <div class="position-relative">
                @yield('navigation')
            </div>
            {{-- @yield('extra-navigation') --}}
            @if (request()->is('teacher/subjects/*'))

                @yield('page-content')
            @else
                @php
                    $_route = ['registrar/dashboard*', 'registrar/enrollment*', 'registrar/semestral-clearance*', 'registrar/sections*', 'registrar/subjects*', 'teacher/subjects*', 'department-head/grade-submission*', 'department-head/semestral-clearance*', 'dean/e-clearance*', 'accounting/fees*', 'accounting/particular/fee*', 'accounting/semestral-clearance*', 'executive/semestral-clearance*', 'librarian/semestral-clearance*', 'administrator/semestral-clearance*'];
                @endphp
                @foreach ($_route as $route)
                    @if (request()->is($route))
                        @yield('sub-navigation')
                    @endif
                @endforeach

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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (Session::has('success'))
            Swal.fire({
            title: 'Complete!',
            text:"{{ session('success') }}",
            icon: 'success',
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
