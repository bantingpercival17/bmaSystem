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
    <style>
        .la-ball-square-clockwise-spin,
        .la-ball-square-clockwise-spin>div {
            position: relative;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .la-ball-square-clockwise-spin {
            display: block;
            font-size: 0;
            color: #fff;
        }

        .la-ball-square-clockwise-spin.la-dark {
            color: #333;
        }

        .la-ball-square-clockwise-spin>div {
            display: inline-block;
            float: none;
            background-color: currentColor;
            border: 0 solid currentColor;
        }

        .la-ball-square-clockwise-spin {
            width: 26px;
            height: 26px;
        }

        .la-ball-square-clockwise-spin>div {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 12px;
            height: 12px;
            margin-top: -6px;
            margin-left: -6px;
            border-radius: 100%;
            -webkit-animation: ball-square-clockwise-spin 1s infinite ease-in-out;
            -moz-animation: ball-square-clockwise-spin 1s infinite ease-in-out;
            -o-animation: ball-square-clockwise-spin 1s infinite ease-in-out;
            animation: ball-square-clockwise-spin 1s infinite ease-in-out;
        }

        .la-ball-square-clockwise-spin>div:nth-child(1) {
            top: 0;
            left: 0;
            -webkit-animation-delay: -.875s;
            -moz-animation-delay: -.875s;
            -o-animation-delay: -.875s;
            animation-delay: -.875s;
        }

        .la-ball-square-clockwise-spin>div:nth-child(2) {
            top: 0;
            left: 50%;
            -webkit-animation-delay: -.75s;
            -moz-animation-delay: -.75s;
            -o-animation-delay: -.75s;
            animation-delay: -.75s;
        }

        .la-ball-square-clockwise-spin>div:nth-child(3) {
            top: 0;
            left: 100%;
            -webkit-animation-delay: -.625s;
            -moz-animation-delay: -.625s;
            -o-animation-delay: -.625s;
            animation-delay: -.625s;
        }

        .la-ball-square-clockwise-spin>div:nth-child(4) {
            top: 50%;
            left: 100%;
            -webkit-animation-delay: -.5s;
            -moz-animation-delay: -.5s;
            -o-animation-delay: -.5s;
            animation-delay: -.5s;
        }

        .la-ball-square-clockwise-spin>div:nth-child(5) {
            top: 100%;
            left: 100%;
            -webkit-animation-delay: -.375s;
            -moz-animation-delay: -.375s;
            -o-animation-delay: -.375s;
            animation-delay: -.375s;
        }

        .la-ball-square-clockwise-spin>div:nth-child(6) {
            top: 100%;
            left: 50%;
            -webkit-animation-delay: -.25s;
            -moz-animation-delay: -.25s;
            -o-animation-delay: -.25s;
            animation-delay: -.25s;
        }

        .la-ball-square-clockwise-spin>div:nth-child(7) {
            top: 100%;
            left: 0;
            -webkit-animation-delay: -.125s;
            -moz-animation-delay: -.125s;
            -o-animation-delay: -.125s;
            animation-delay: -.125s;
        }

        .la-ball-square-clockwise-spin>div:nth-child(8) {
            top: 50%;
            left: 0;
            -webkit-animation-delay: 0s;
            -moz-animation-delay: 0s;
            -o-animation-delay: 0s;
            animation-delay: 0s;
        }

        .la-ball-square-clockwise-spin.la-sm {
            width: 12px;
            height: 12px;
        }

        .la-ball-square-clockwise-spin.la-sm>div {
            width: 6px;
            height: 6px;
            margin-top: -3px;
            margin-left: -3px;
        }

        .la-ball-square-clockwise-spin.la-2x {
            width: 52px;
            height: 52px;
        }

        .la-ball-square-clockwise-spin.la-2x>div {
            width: 24px;
            height: 24px;
            margin-top: -12px;
            margin-left: -12px;
        }

        .la-ball-square-clockwise-spin.la-3x {
            width: 78px;
            height: 78px;
        }

        .la-ball-square-clockwise-spin.la-3x>div {
            width: 36px;
            height: 36px;
            margin-top: -18px;
            margin-left: -18px;
        }

        /*
 * Animation
 */
        @-webkit-keyframes ball-square-clockwise-spin {

            0%,
            40%,
            100% {
                -webkit-transform: scale(.4);
                transform: scale(.4);
            }

            70% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }

        @-moz-keyframes ball-square-clockwise-spin {

            0%,
            40%,
            100% {
                -moz-transform: scale(.4);
                transform: scale(.4);
            }

            70% {
                -moz-transform: scale(1);
                transform: scale(1);
            }
        }

        @-o-keyframes ball-square-clockwise-spin {

            0%,
            40%,
            100% {
                -o-transform: scale(.4);
                transform: scale(.4);
            }

            70% {
                -o-transform: scale(1);
                transform: scale(1);
            }
        }

        @keyframes ball-square-clockwise-spin {

            0%,
            40%,
            100% {
                -webkit-transform: scale(.4);
                -moz-transform: scale(.4);
                -o-transform: scale(.4);
                transform: scale(.4);
            }

            70% {
                -webkit-transform: scale(1);
                -moz-transform: scale(1);
                -o-transform: scale(1);
                transform: scale(1);
            }
        }
    </style>
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
        window.addEventListener('swal:confirmInputVoid', event => {
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
                    Livewire.emit(event.detail.method, event.detail.params.payment, value)
                },
            });
        })
        window.addEventListener('submit:form', event => {
            //alert('Name updated to: ' + event.detail.form);
            document.getElementById(event.detail.form).submit()
        })
        window.addEventListener('name-updated', event => {
            window.addEventListener('swal:')
            alert('Name updated to: ' + event.detail.newName);
        })
        @if(Session::has('success'))
        Swal.fire({
            title: 'Complete!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Okay'
        })
        /* toastr.success("{{ session('message') }}") */
        @endif
        @if(Session::has('error'))
        Swal.fire({
            title: 'Existing Data!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'Okay'
        })
        /* toastr.success("{{ session('message') }}") */
        @endif
        window.addEventListener('show-loading', () => {
            document.getElementById('loading').style.display = 'block';
            console.log('block')
        });

        window.addEventListener('hide-loading', () => {
            document.getElementById('loading').style.display = 'none';
            console.log('none')
        });
    </script>
    @yield('script')
</body>

</html>