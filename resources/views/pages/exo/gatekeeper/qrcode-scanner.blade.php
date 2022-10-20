<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/image/bma-logo-1.png') }}">
    <title>Qr Code Scanner | Baliwag Maritime Academy Inc.</title>


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

    <main class="main-content">
        <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar py-lg-0">
            <div class="container-fluid navbar-inner">

                <a href="{{-- {{ route('website.home') }} --}}" class="navbar-brand">
                    <img src="{{ asset('assets/image/bma-logo-1.png') }}" alt="image"
                        class="img-fluid rounded-circle avatar-70">
                    <h2 class="logo-title me-3">BALIWAG MARITIME ACADEMY</h2>
                    <span class="app badge-4 ">
                    </span>
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

                    </ul>
                    </li>
                    <span class="text-info real-time"></span>
                    </li>

                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">

            <div class="editors position-relative mt-5 ">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <video id="preview" style="width:100%"></video>

                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <a href="{{ route('exo.qrcode-scanner') }}?_user=employee"
                                                class="btn {{ request()->input('_user') ? (request()->input('_user') == 'employee' ? 'btn-primary' : 'btn-secondary') : 'btn-secondary' }} w-100 mt-2 mb-2">EMPLOYEE</a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('exo.qrcode-scanner') }}?_user=student"
                                                class="btn {{ request()->input('_user') ? (request()->input('_user') == 'student' ? 'btn-primary' : 'btn-secondary') : 'btn-secondary' }} w-100 mt-2 mb-2">STUDENT</a>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-sm" onclick="statusCamera(true);">On
                                        Camera</button>

                                    <button class="btn btn-secondary btn-sm" onclick="statusCamera(false);">Off
                                        Camera</button>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-8">
                            @if (request()->input('_user') == 'employee')
                                <div class="card mb-2">
                                    <div class="row no-gutters">
                                        <div class="col-md-3">
                                            <img src="http://20.0.0.120:70/assets/img/staff/avatar.png"
                                                class="card-img image" alt="#">
                                        </div>
                                        <div class="col-md ps-0">
                                            <div class="card-body p-3 me-2">
                                                <label for=""
                                                    class="fw-bolder text-primary h4 text-name employee-name">
                                                    NAME OF EMPLOYEE
                                                </label>
                                                <p class="mb-0">
                                                    <small class="fw-bolder badge bg-secondary text-department">
                                                        DEPARTMENT
                                                    </small>
                                                </p>
                                                <div class="row">
                                                    <div class="col-md">
                                                        <label for="" class="fw-bolder text-muted h5">
                                                            TIME IN
                                                        </label> <br>
                                                        <label for=""
                                                            class="fw-bolder text-info h4 employee-time-in"> - - : -
                                                            -
                                                        </label>
                                                    </div>
                                                    <div class="col-md">
                                                        <label for="" class="fw-bolder text-muted h5">
                                                            TIME OUT
                                                        </label> <br>
                                                        <label for=""
                                                            class="fw-bolder text-info h4 employee-time-out"> - - : -
                                                            -
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (request()->input('_user') == 'student')
                                <div class="card mb-2">
                                    <div class="row no-gutters">
                                        <div class="col-md-3">
                                            <img src="http://20.0.0.120/img/student-picture/midship-man.jpg"
                                                class="card-img" alt="#">
                                        </div>
                                        <div class="col-md ps-0">
                                            <div class="card-body p-3 me-2">
                                                <div class="row">
                                                    <div class="col-md">
                                                        <label for=""
                                                            class="fw-bolder text-primary h4 student-name">
                                                            MIDSHIPMAN NAME
                                                        </label>
                                                        <p class="mb-0">
                                                            <small class="fw-bolder badge bg-secondary student-course">
                                                                COURSE
                                                            </small> -
                                                            <small class="badge bg-primary student-level">
                                                                YEAR LEVEL
                                                            </small>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">

                                                        <div class="col-md">
                                                            <label for="" class="fw-bolder text-muted h5">
                                                                TIME IN
                                                            </label> <br>
                                                            <label for=""
                                                                class="fw-bolder text-info h4 student-time-in"> -
                                                                - : -
                                                                -
                                                            </label>
                                                        </div>
                                                        <div class="col-md">
                                                            <label for="" class="fw-bolder text-muted h5">
                                                                TIME OUT
                                                            </label> <br>
                                                            <label for=""
                                                                class="fw-bolder text-info h4 student-time-out"> -
                                                                - : -
                                                                -
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (request()->input('_user') == 'employee')
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <p class="h4 text-primary"><b>ATTENDANCE LIST</b></p>
                                    </div>
                                    <div class="card">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>EMPLOYEE</th>
                                                    <th>TIME IN</th>
                                                    <th>TIME OUT</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-body-100">
                                                @if (count($_employees) > 0)
                                                    @foreach ($_employees as $_data)
                                                        <tr>
                                                            <td>
                                                                <span class="text-muted">
                                                                    {{ strtoupper($_data->staff->first_name . ' ' . $_data->staff->last_name) }}<br>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="text-muted">
                                                                    {{ $_data->staff ? ($_data->time_in ? date_format(date_create($_data->time_in), 'h:i:s a') : '-') : '-' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="text-muted">
                                                                    {{ $_data->staff ? ($_data->time_in ? ($_data->time_out != null ? date_format(date_create($_data->time_out), 'h:i:s a') : '-') : '-') : '-' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="3"><b>NO DATA</b></td>
                                                    </tr>
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if (request()->input('_user') == 'student')
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <p class="h4 text-success"><b>ATTENDANCE LIST</b></p>
                                    </div>
                                    <div class="card">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>CADET'S NAME</th>
                                                    <th>SECTION</th>
                                                    <th>TIME IN</th>
                                                    <th>TIME OUT</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-body-100">
                                                @if (count($_students) > 0)
                                                    @foreach ($_students as $_data)
                                                        <tr>
                                                            <td>
                                                                <span class="text-muted">
                                                                    {{ strtoupper($_data->student->first_name . ' ' . $_data->student->last_name) }}<br>
                                                                </span>
                                                            </td>
                                                            <td> {{ $_data->student->current_section->section->section_name }}
                                                            </td>
                                                            <td>
                                                                <span class="text-muted">
                                                                    {{ $_data->student ? ($_data->time_in ? date_format(date_create($_data->time_in), 'h:i:s a') : '-') : '-' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{ $_data->student ? ($_data->time_in ? ($_data->time_out != null ? date_format(date_create($_data->time_out), 'h:i:s a') : '-') : '-') : '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="3"><b>NO DATA</b></td>
                                                    </tr>
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                </div>
            </div>
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
        var audio_success = new Audio("{{ asset('assets/audio/beep-07a.mp3') }}");
        var audio_error = new Audio("{{ asset('assets/audio/beep-10.mp3') }}");
        var user_select = new Audio("{{ asset('assets/audio/user_select.mp3') }}")
        var audioCadetTimeIn = new Audio("{{ asset('assets/audio/cadet_timein.mp3') }}");
        var audioCadetTimeOut = new Audio("{{ asset('assets/audio/cadet_timeout.mp3') }}");
        var audio_custom = new Audio("{{ asset('assets/audio/invalid_qr_code_1.mp3') }}");
        var user = "{{ request()->input('_user') }}";
    </script>
    <script src="{{ asset('js/moments.js') }}"></script>
    <script src="{{ asset('/js/instascan.min.js') }}"></script>
    <script src="{{ asset('js/moments.js') }}"></script>
    <script src="{{ asset('js/qr-code-scanner.js') }}"></script>



</body>

</html>
