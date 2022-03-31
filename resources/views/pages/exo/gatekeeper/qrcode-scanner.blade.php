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
                    <!-- PERCI PAKI ENABLE LANG SANA TO KUNG NKA LOGIN UNG USERS-->

                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">

            <div class="editors position-relative mt-5 ">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <video id="preview" style="width:100%"></video>

                                    <div class="form-group row">
                                        <div class="col-md">
                                            <div class="form-check d-block ">
                                                <input class="form-check-input" type="radio" name="options"
                                                    id="options1" autocomplete="off" value="1" checked>
                                                <label class="form-check-label" for="options1">
                                                    Front Camera
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-check d-block col-md">
                                                <input class="form-check-input" type="radio" name="options"
                                                    id="options2" autocomplete="off" value="2">
                                                <label class="form-check-label" for="options2">
                                                    Back Camera
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md">
                            <div class="card bg-white iq-service-card">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <a href="{{ route('exo.qrcode-scanner') }}?_user=employee"
                                                class="btn {{ request()->input('_user')? (request()->input('_user') == 'employee'? 'btn-primary': 'btn-secondary'): 'btn-secondary' }} w-100 mt-2 mb-2">EMPLOYEE</a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('exo.qrcode-scanner') }}?_user=student"
                                                class="btn {{ request()->input('_user')? (request()->input('_user') == 'student'? 'btn-primary': 'btn-secondary'): 'btn-secondary' }} w-100 mt-2 mb-2">STUDENT</a>
                                        </div>
                                    </div>
                                    @if (request()->input('_user') == 'employee')
                                        <div class="row">
                                            <div class="col-6">
                                                <img src="{{ asset('/assets/img/staff/avatar.png') }}"
                                                    alt="user-avatar" class="rounded img-fluid  image">
                                            </div>
                                            <div class="col-6">
                                                <span class="text-muted  h4">
                                                    <small> <b class="text-status">TIME</b></small>
                                                </span><br>
                                                <span class="text-success h3">
                                                    <b class="text-time">TIME IN / TIME OUT</b><br>

                                                </span><br>
                                                <span class="text-info h5">
                                                    <b class="text-name">NAME OF EMPLOYEE</b><br>

                                                </span>
                                                <span class="text-muted  h5">
                                                    <small> <b class="text-department">OFFICE DEPARTMENT</b></small>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    @if (request()->input('_user') == 'student')
                                        <div class="row">
                                            <div class="col-6">
                                                <img src="{{ asset('/assets/img/student-picture/midship-man.jpg') }}"
                                                    alt="user-avatar" class="rounded img-fluid  image"
                                                    onerror="imageError()">
                                            </div>
                                            <div class="col-6">
                                                <span class="text-muted  h4">
                                                    <small> <b class="text-status">TIME</b></small>
                                                </span><br>
                                                <span class="text-success h3">
                                                    <b class="text-time">TIME IN / TIME OUT</b><br>

                                                </span><br>
                                                <span class="text-info h5">
                                                    <b class="text-name">CADET NAME</b><br>

                                                </span>
                                                <span class="text-muted  h5">
                                                    <small> <b class="text-department">COURSE</b></small>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
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
            text:"{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'Okay'
            })
            /* toastr.success("{{ session('message') }}") */
        @endif

        $('.validate-checkbox').click(function() {
            var data = $(this).data('input'),
                check = $(this).prop('checked')
            if (check == false) {
                $('.' + data).prop('disabled', false)
            } else {
                $('.' + data).prop('disabled', true)
            }
            console.log(data)

        })
    </script>
    <script src="{{ asset('js/moments.js') }}"></script>
    <script src="{{ asset('/js/instascan.min.js') }}"></script>
    <script src="{{ asset('js/moments.js') }}"></script>
    <script type="text/javascript">
        setInterval(() => {
            let currentDate = new Date();
            $('.real-time').text(currentDate)
            /*   table_data(); */
        }, 500);
        var audio_success = new Audio("{{ asset('assets/audio/beep-07a.mp3') }}");
        var audio_erroe = new Audio("{{ asset('assets/audio/beep-10.mp3') }}");
        var user_select = new Audio("{{ asset('assets/audio/user_select.mp3') }}")
        var audioCadetTimeIn = new Audio("{{ asset('assets/audio/cadet_timein.mp3') }}");
        var audioCadetTimeOut = new Audio("{{ asset('assets/audio/cadet_timeout.mp3') }}");
        var user = "{{ request()->input('_user') }}"
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
                $('[name="options"]').on('change', function() {
                    if ($(this).val() == 1) {
                        if (cameras[0] != "") {
                            scanner.start(cameras[0]);
                        } else {
                            alert('No Front camera found!');
                        }
                    } else if ($(this).val() == 2) {
                        if (cameras[1] != "") {
                            scanner.start(cameras[1]);
                        } else {
                            alert('No Back camera found!');
                        }
                    }
                });
            } else {
                console.error('No cameras found.');
                alert('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });
        var scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            scanPeriod: 5,
            mirror: true
        });

        scanner.addListener('scan', function(content) {
            if (user) {
                if (user == 'employee') {
                    scannerQrcodeEmployee(content)
                }
                if (user == 'student') {

                    scannerQrcodeStudent(content)
                }
            } else {
                alertNotification('Error!', "Please select a User", 'error')
                audio_erroe.play()
                user_select.play()
            }


        });

        function scannerQrcodeStudent(_data) {
            //audioCadetTimeIn.play()
            $.get('/executive/attendance-checket/scan-code/' + user + '/' + _data, function(res) {
                var res = res._data._data;
                console.log(res)
                if (res.respond == 200) {
                    audio_success.play()
                    var file_name = res.details.link
                    var audio_custom = new Audio(file_name);
                    audio_custom.play()
                    alertNotification(res.time_status, res.message, 'success')
                    displayData(res.details)
                }
                if (res.respond == 404) {
                    var file_name = res.details.link
                    var audio_custom = new Audio(file_name);
                    audio_custom.play()
                    alertNotification('Error!', res.message, 'error')
                }
            }).fail(function() {
                alertNotification('Error!', 'Invalid QR Code', 'error')
                audio_erroe.play()
                var audio_custom = new Audio("{{ asset('assets/audio/invalid_qr_code_1.mp3') }}");
                audio_custom.play()
                clear_details()
            })
        }

        function scannerQrcodeEmployee(_data) {
            table_data()
            $.get('scan-code-v2/' + _data, function(data) {
                if (data._data.respond == 200) {
                    //toastr.success(data._data.message, data._data.data.time_status)
                    audio_success.play()
                    employee_details(data._data)
                    var file_name = data._data.data.link
                    var audio_custom = new Audio(file_name);
                    audio_custom.play()
                    alertNotification(data._data.data.time_status, data._data.message, 'success')
                }
                if (data._data.respond == 404) {
                    audio_erroe.play()
                    //toastr.error(data._data.message, 'Error!')
                    var file_name = data._data.data.link
                    var audio_custom = new Audio(file_name);
                    audio_custom.play()
                    alertNotification('Error!', data._data.message, 'error')
                    clear_details()
                }
            }).fail(function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid QR Code',
                    icon: 'error',
                })
                // toastr.error('Invalid QR Code.', 'Error!')
                audio_erroe.play()
                var audio_custom = new Audio("{{ asset('assets/audio/invalid_qr_code_1.mp3') }}");
                audio_custom.play()
                clear_details()
            })

        }

        function table_data() {
            $.get('/executive/fetch-attendance', function(respond) {
                $('.table-body-100').empty()
                if (respond._data.length == 0) {
                    $('.table-body-100').append(
                        "<tr>" +
                        "<td colspan='3'> <b> No Data </b> </td>" +
                        "</tr>"
                    );
                } else {
                    respond._data.forEach(data => {
                        var time_out = data.time_out != null ? data.time_out : '-';

                        $('.table-body-100').append(
                            "<tr>" +
                            "<td>" + data.first_name + " " + data.last_name + "</td>" +
                            "<td>" + data.time_in + "</td>" +
                            "<td>" + time_out + "</td>" +

                            "</tr>"
                        );
                    });
                }
            });
        }

        function displayData(data) {
            $('.text-status').text(data.time_status);
            $('.text-time').text(data.time)
            $('.text-name').text(data.name);
            $('.text-department').text(data.course)
            $('.image').attr('src', data.image)
        }

        function employee_details(_data) {
            $('.text-status').text(_data.data.time_status);
            $('.text-time').text(_data.data.time)
            $('.text-name').text(_data.data.name);
            $('.text-department').text(_data.data.department)
            $('.image').attr('src', "/assets/img/staff/" + _data.data.image)
        }

        function imageError() {
            $('.image').attr('src', "/assets/img/student-picture/midship-man.jpg")
        }

        function clear_details() {
            $('.text-status').text("TIME");
            $('.text-time').text('TIME IN / TIME OUT')
            $('.text-name').text("NAME OF EMPLOYEE");
            $('.text-department').text('OFFICE DEPARTMENT')
            $('.image').attr('src', "{{ asset('/assets/img/staff/avatar.png') }}")
        }

        function alertNotification(title, message, icon) {
            let timerInterval
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
            })
        }
    </script>


</body>

</html>
