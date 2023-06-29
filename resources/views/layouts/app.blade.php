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
        @if (request()->is('executive/scanner'))
            @livewire('components.top-navigation-scanner')
            {{ $slot }}
        @else
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
        @endif
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
    <script src="{{ asset('assets\plugins\sweetalert2\sweetalert2.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets\plugins\sweetalert2\sweetalert2.min.css') }}">
    @if (request()->is('executive/scanner'))
        <script>
            function updateTime() {
                console.log('working')
                Livewire.emit('updateTime')

            }
            setInterval(updateTime, 1000);
            // Add an event listener for the barcode scanner
            window.addEventListener("keydown", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    document.getElementById("qr-code-scanner").focus();
                }
            });
            window.addEventListener('qrcode:alert', event => {
                console.log('qrcode:alert')
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: event.detail.type,
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                })
                var audio_custom = new Audio(event.detail.audio);
                audio_custom.play()
            })
        </script>
    @endif
    <script>
        window.addEventListener('swal:alert', event => {
            console.log('show:alert')
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.type,
            });
        })
        window.addEventListener('swal:confirm', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.type,
                showCancelButton: true,
                cancelButtonText: event.detail.cancelButtonText,
                confirmButtonText: event.detail.confirmButtonText,
                preConfirm: function(value) {
                    Livewire.emit(event.detail.method, event.detail.params.data)
                },
            });
        })
        window.addEventListener('swal:confirmInput', event => {
            console.log('confirmInput')
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.type,
                showCancelButton: true,
                cancelButtonText: event.detail.cancelButtonText,
                confirmButtonText: event.detail.confirmButtonText,
                input: event.detail.input,
                inputPlaceholder: event.detail.inputPlaceholder,
                preConfirm: function(value) {
                    Livewire.emit(event.detail.method, event.detail.params.applicant, event.detail
                        .params.result, value)
                },
            });
        })
        window.addEventListener('swal:confirmInputStudent', event => {
            console.log('confirmInput')
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.type,
                showCancelButton: true,
                cancelButtonText: event.detail.cancelButtonText,
                confirmButtonText: event.detail.confirmButtonText,
                input: event.detail.input,
                inputPlaceholder: event.detail.inputPlaceholder,
                preConfirm: function(value) {
                    Livewire.emit(event.detail.method, event.detail.params.student, event.detail.params
                        .enrollment, event.detail.params.result, value)
                },
            });
        })
        window.addEventListener('name-updated', event => {
            window.addEventListener('swal:')
            alert('Name updated to: ' + event.detail.newName);
        })
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
        $('.btn-assessment').click(function(event) {
            Swal.fire({
                title: 'Enrollment Assessment',
                text: "Do you want to submit?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {
                    console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        })
    </script>
    @yield('script')
</body>

</html>
