<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Monitoring | Baliwag Maritime Academy, Inc.</title>
    <link rel="shortcut icon" href="{{ asset('assets/image/bma-logo-1.png') }}" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <style>
        #preview {
            width: 490px;

        }

        @media only screen and (max-width:1000px) {
            #preview {
                width: 100%;

            }
        }

    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="/" class="navbar-brand">
                    <img src="{{ asset('assets/image/bma-logo-1.png') }}" alt="AdminLTE Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">Baliwag Maritime Academy, Inc.</span>
                </a>
                <span class="float-rigth">
                    <span class="brand-text font-weight-light real-time h5">{{ date('M  d, Y h:m a') }}</span>
                    {{-- <span class="text-muted h3"><b>DATE AND TIME: </b></span> <span class="h2 text-success">
                        <b>{{ date('M d,Y h:m:s a') }}</b></span> --}}
                </span>
            </div>
        </nav>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">


                        </div>
                    </div>
                </div>
            </div>
            <div class="container">

                <div class="row">
                    <div class=" col-lg-6 col-md-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <video id="preview"></video>
                                <div class="btn-group btn-group-smaill btn-group-toggle mb-5" data-toggle="buttons">
                                    <label class="btn btn-primary btn-small active">
                                        <input type="radio" name="options" value="1" autocomplete="off" checked> Front
                                        Camera
                                    </label>
                                    <label class="btn btn-secondary btn-small">
                                        <input type="radio" name="options" value="2" autocomplete="off"> Back Camera
                                    </label>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class=" col-xs col-md col-xs-12">

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <img src="{{ asset('/assets/img/staff/avatar.png') }}" alt="user-avatar"
                                            class="img-circle img-fluid  image">
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
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <p class="h4 text-success"><b>Attendance List</b></p>
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
                                @if ($_employees->count() > 0)
                                    @foreach ($_employees as $_data)
                                        <tr>
                                            <td>
                                                <span class="text-muted">
                                                    {{ strtoupper($_data->first_name . ' ' . $_data->last_name) }}<br>
                                                </span>
                                            </td>
                                            <td>
                                                @if ($_data->staff_id)
                                                    @if ($_data->daily_attendance)
                                                        <span class="text-muted">
                                                            {{ date_format(date_create($_data->daily_attendance->time_in), 'h:i:s a') }}
                                                        </span>
                                                    @else
                                                        <span class="h4 text-muted">
                                                            -
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="h4 text-muted">
                                                        -
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($_data->staff_id)
                                                    @if ($_data->daily_attendance)
                                                        @if ($_data->daily_attendance->time_out != null)
                                                            <span class="text-muted">
                                                                {{ date_format(date_create($_data->daily_attendance->time_out), 'h:i:s a') }}
                                                            </span>
                                                        @else
                                                            <span class="h4 text-muted">
                                                                -
                                                            </span>
                                                        @endif

                                                    @else
                                                        <span class="h4 text-muted">
                                                            -
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="h4 text-muted">
                                                        -
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3"><b>No Data</b></td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer">

            <strong><a href="https://adminlte.io">bma.edu.ph</a>.</strong> All rights
            reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('js/moments.js') }}"></script>
    <script>
        // BS-Stepper Init
        /*  document.addEventListener('DOMContentLoaded', function() {
             window.stepper = new Stepper(document.querySelector('.bs-stepper'))
         }) */
    </script>
    <script src="{{ asset('/js/instascan.min.js') }}"></script>
    <script type="text/javascript">
        setInterval(() => {
            let currentDate = new Date();
            $('.real-time').text(currentDate)
            /*   table_data(); */
        }, 500);
        var audio_success = new Audio("{{ asset('assets/audio/beep-07a.mp3') }}");
        var audio_erroe = new Audio("{{ asset('assets/audio/beep-10.mp3') }}");
        //document.getElementById('mySound').play();
        var scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            scanPeriod: 5,
            mirror: true
        });
        scanner.addListener('scan', function(content) {
            table_data()
            //alert(content)
            scan_qr_code_v2(content)

        });
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
            //alert(e);
        });


        function scan_qr_code_v2(_data) {
            table_data()
            $.get('scan-code-v2/' + _data, function(data) {

                if (data._data.respond == 200) {
                    toastr.success(data._data.message, data._data.data.time_status)

                    audio_success.play()
                    employee_details(data._data)
                    var file_name = data._data.data.link
                    var audio_custom = new Audio(file_name);
                    audio_custom.play()
                }
                if (data._data.respond == 404) {
                    audio_erroe.play()
                    toastr.error(data._data.message, 'Error!')
                    var file_name = data._data.data.link
                    var audio_custom = new Audio(file_name);
                    audio_custom.play()
                    clear_details()
                }
            }).fail(function() {
                toastr.error('Invalid QR Code.', 'Error!')
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

        function employee_details(_data) {
            $('.text-status').text(_data.data.time_status);
            $('.text-time').text(_data.data.time)
            $('.text-name').text(_data.data.name);
            $('.text-department').text(_data.data.department)
            $('.image').attr('src', "/assets/img/staff/" + _data.data.image)
        }

        function clear_details() {
            $('.text-status').text("TIME");
            $('.text-time').text('TIME IN / TIME OUT')
            $('.text-name').text("NAME OF EMPLOYEE");
            $('.text-department').text('OFFICE DEPARTMENT')
            $('.image').attr('src', "{{ asset('/assets/img/staff/avatar.png') }}")
        }
    </script>
</body>

</html>
