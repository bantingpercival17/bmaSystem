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
<<<<<<< HEAD
    @livewire('components.side-navigation-menu')
    <main class="main-content">
        <div class="position-relative">
            @livewire('components.top-navigation-menu')
        </div>
        <div style="margin-top:7%;">

            <div class="conatiner-fluid content-inner">
=======
        @livewire('components.side-navigation-menu')
        <main class="main-content">
            <div class="position-relative">
                @livewire('components.top-navigation-menu')
            </div>
            <div style="margin-top:7%;">

                <div class="conatiner-fluid content-inner">
>>>>>>> 3e6820fdf269866d3f26434445b47e26d7800799


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
<<<<<<< HEAD
    <!--  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="{{ asset('resources/plugin/select/js/select2.min.js') }}"></script> -->
    <script src="{{ asset('resources/plugin/toastify/toastify.js') }}"></script>
    <script src="{{ asset('resources/plugin/editor/editor.js') }}"></script>
    @yield('script')
=======
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}


    <script src="{{ asset('resources/plugin/select/js/select2.min.js') }}"></script>
    <script src="{{ asset('resources/plugin/toastify/toastify.js') }}"></script>
    <script src="{{ asset('resources/plugin/editor/editor.js') }}"></script>
    
    @stack('scripts')
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
        @if (Session::has('warning'))
            Swal.fire({
                title: 'System Maintaince!',
                text: "{{ session('warning') }}",
                icon: 'warning',
                confirmButtonText: 'Okay'
            })
            /* toastr.success("{{ session('message') }}") */
        @endif
        var message = "<?php echo session('reset-password'); ?>"
        @if (Session::has('reset-password'))
            Swal.fire({
                title: 'Complete!',
                text: /* "{{ session('reset-password') }}" */ message,
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
        $('.select').select2()
    </script>
    @yield('scripts')
>>>>>>> 3e6820fdf269866d3f26434445b47e26d7800799
</body>

</html>