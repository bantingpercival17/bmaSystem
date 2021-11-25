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
                    <div class="col-md-6 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <video id="preview"></video>
                            </div>
                            <div class="card-body">
                                <div class="bs-stepper">
                                    <div class="bs-stepper-header" role="tablist">
                                        <div class="step" data-target="#tab-container-1">
                                            <button type="button" class="step-trigger" role="tab"
                                                aria-controls="tab-container-1" id="tab-container-1-trigger">
                                                <span class="bs-stepper-circle">1</span>
                                                <small>
                                                    <b>
                                                        <span class="bs-stepper-label">TEMPERATURE</span>
                                                    </b>

                                                </small>

                                            </button>
                                        </div>
                                        <div class="bs-stepper-line"></div>
                                        <div class="step" data-target="#tab-container-2">
                                            <button type="button" class="step-trigger" role="tab"
                                                aria-controls="tab-container-2" id="tab-container-2-trigger">
                                                <span class="bs-stepper-circle">2</span>
                                                <small><b> <span class="bs-stepper-label">SYMPTOMS</span></b></small>
                                            </button>
                                        </div>
                                        <div class="bs-stepper-line"></div>
                                        <div class="step" data-target="#tab-container-3">
                                            <button type="button" class="step-trigger" role="tab"
                                                aria-controls="tab-container-3" id="tab-container-3-trigger">
                                                <span class="bs-stepper-circle">3</span>
                                                <small><b> <span class="bs-stepper-label">EXPOSURE</span></b></small>
                                            </button>
                                        </div>
                                        <div class="bs-stepper-line"></div>
                                        <div class="step" data-target="#tab-container-4">
                                            <button type="button" class="step-trigger" role="tab"
                                                aria-controls="tab-container-4" id="tab-container-3-trigger">
                                                <span class="bs-stepper-circle">4</span>
                                                <small><b><span class="bs-stepper-label">COVID</span></b></small>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bs-stepper-content">
                                        <form action="attendance" method="post">
                                            @csrf
                                            <div id="tab-container-1" class="content" role="tabpanel"
                                                aria-labelledby="tab-container-1-trigger">
                                                <label class="text-success">| EMPLOYEE DETAILS</label>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <label for="" class="text-muted">FULL
                                                                NAME</label>
                                                            <span
                                                                class="form-control full-name">{{ old('name') }}</span>
                                                            <input type="hidden" class="form-control full-name"
                                                                placeholder="Employee Name" name="name"
                                                                value="{{ old('name') }}">
                                                            <input type="hidden" name="employee" class="employee"
                                                                value="{{ old('employee') }}">
                                                            @error('employee')
                                                                <small class="text-danger h6"><b>
                                                                        {{ $message }}</b></small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="" class="text-muted">BODY
                                                                TEMPERATURE</label>
                                                            <input type="text" class="form-control" name="body_temp"
                                                                value="{{ old('body_temp') }}">
                                                            @error('body_temp')
                                                                <small class="text-danger h6"><b>
                                                                        {{ $message }}</b></small>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>
                                                <a class="btn btn-primary" onclick="stepper.next()">Next</a>

                                            </div>
                                            <div id="tab-container-2" class="content" role="tabpanel"
                                                aria-labelledby="tab-container-2-trigger">

                                                <div class="form-group">
                                                    <label for="" class="text-muted">DO YOU HAVE ANY
                                                        OF THE
                                                        FOLLOWING?</label>
                                                    @php
                                                        $_datas = ['Cough', 'Cold', 'Diarrhea', 'Sore Throat', 'Body Aches', 'Headaches', 'Loss of Smell', 'Difficulty in Breathing', 'None of the Above'];
                                                    @endphp
                                                    <div class="form-group clearfix">
                                                        @foreach ($_datas as $_key => $_data)
                                                            @php
                                                                $_status = '';
                                                                if (old('question1')) {
                                                                    foreach (old('question1') as $ans) {
                                                                        if ($_data == $ans) {
                                                                            $_status = 'checked';
                                                                        }
                                                                    }
                                                                }
                                                                
                                                            @endphp
                                                            <div class="icheck-success d-inline">
                                                                <input type="checkbox"
                                                                    id="checkboxSuccess{{ $_key }}"
                                                                    name="question1[]" value="{{ $_data }}"
                                                                    {{ $_status }}>
                                                                <label for="checkboxSuccess{{ $_key }}"
                                                                    class="text-muted">
                                                                    {{ $_data }}
                                                                </label>
                                                            </div>
                                                            <br>
                                                        @endforeach

                                                    </div>
                                                    @error('question1')
                                                        <small class="text-danger h6"><b>
                                                                {{ $message }}</b></small>
                                                    @enderror
                                                </div>
                                                <a class="btn btn-primary" onclick="stepper.previous()">Previous</a>
                                                <a class="btn btn-primary" onclick="stepper.next()">Next</a>
                                            </div>
                                            <div id="tab-container-3" class="content" role="tabpanel"
                                                aria-labelledby="tab-container-3-trigger">
                                                <div class="form-group">
                                                    <label for="" class="text-muted">DO YOU
                                                        EXPERIENCE ANY OF THE
                                                        FOLLOWING?</label>
                                                    @php
                                                        $_datas = ['Travelled during the last 15 days', 'Direct Contact with a COVID-19 Patient', 'Direct Contact with PUI / PUM', 'None of the Above'];
                                                    @endphp
                                                    <div class="form-group clearfix">
                                                        @foreach ($_datas as $_key => $_data)
                                                            @php
                                                                $_status = '';
                                                                if (old('question2')) {
                                                                    foreach (old('question2') as $ans) {
                                                                        if ($_data == $ans) {
                                                                            $_status = 'checked';
                                                                        }
                                                                    }
                                                                }
                                                                
                                                            @endphp
                                                            <div class="icheck-success d-inline">
                                                                <input type="checkbox"
                                                                    id="checkboxSuccess2{{ $_key }}"
                                                                    name="question2[]" value="{{ $_data }}"
                                                                    {{ $_status }}>
                                                                <label for="checkboxSuccess2{{ $_key }}"
                                                                    class="text-muted">
                                                                    {{ $_data }}
                                                                </label>
                                                            </div>
                                                            <br>
                                                        @endforeach

                                                    </div>
                                                    @error('question2')
                                                        <small class="text-danger h6"><b>
                                                                {{ $message }}</b></small>
                                                    @enderror
                                                </div>
                                                <a class="btn btn-primary" onclick="stepper.previous()">Previous</a>
                                                <a class="btn btn-primary" onclick="stepper.next()">Next</a>
                                            </div>
                                            <div id="tab-container-4" class="content" role="tabpanel"
                                                aria-labelledby="tab-container-4-trigger">
                                                <div class="form-group">
                                                    <label for="" class="text-muted">Have you tested
                                                        COVID-19
                                                        positive in the last
                                                        15 days?</label>
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="question3" id="radioSuccess1"
                                                                value="YES"
                                                                {{ old('question3') == 'YES' ? 'checked' : '' }}>
                                                            <label for="radioSuccess1" class="text-muted">YES
                                                            </label>
                                                        </div>
                                                        <br>
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="question3" id="radioSuccess2"
                                                                value="NO"
                                                                {{ old('question3') == 'NO' ? 'checked' : '' }}>
                                                            <label for="radioSuccess2" class="text-muted">
                                                                NO
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @error('question3')
                                                        <small class="text-danger h6"><b>
                                                                {{ $message }}</b></small>
                                                    @enderror
                                                </div>
                                                <a class="btn btn-primary" onclick="stepper.previous()">Previous</a>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                                {{-- <form action="attendance" method="post">
                                    @csrf
                                    <label class="text-success">| EMPLOYEE DETAILS</label>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="" class="text-muted">FULL
                                                    NAME</label>
                                                <span class="form-control full-name">{{ old('name') }}</span>
                                                <input type="hidden" class="form-control full-name"
                                                    placeholder="Employee Name" name="name"
                                                    value="{{ old('name') }}">
                                                <input type="hidden" name="employee" class="employee"
                                                    value="{{ old('employee') }}">
                                                @error('employee')
                                                    <small class="text-danger h6"><b>
                                                            {{ $message }}</b></small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="" class="text-muted">BODY
                                                    TEMPERATURE</label>
                                                <input type="text" class="form-control" name="body_temp"
                                                    value="{{ old('body_temp') }}">
                                                @error('body_temp')
                                                    <small class="text-danger h6"><b>
                                                            {{ $message }}</b></small>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                    <label class="text-success">| HEALTH CHECK</label>

                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="" class="text-muted">DO YOU HAVE ANY
                                                    OF THE
                                                    FOLLOWING?</label>
                                                @php
                                                    $_datas = ['Cough', 'Cold', 'Diarrhea', 'Sore Throat', 'Body Aches', 'Headaches', 'Loss of Smell', 'None of the Above'];
                                                @endphp
                                                <div class="form-group clearfix">
                                                    @foreach ($_datas as $_key => $_data)
                                                        @php
                                                            $_status = '';
                                                            if (old('question1')) {
                                                                foreach (old('question1') as $ans) {
                                                                    if ($_data == $ans) {
                                                                        $_status = 'checked';
                                                                    }
                                                                }
                                                            }
                                                            
                                                        @endphp
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox"
                                                                id="checkboxSuccess{{ $_key }}"
                                                                name="question1[]" value="{{ $_data }}"
                                                                {{ $_status }}>
                                                            <label for="checkboxSuccess{{ $_key }}"
                                                                class="text-muted">
                                                                {{ $_data }}
                                                            </label>
                                                        </div>
                                                        <br>
                                                    @endforeach

                                                </div>
                                                @error('question1')
                                                    <small class="text-danger h6"><b>
                                                            {{ $message }}</b></small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="" class="text-muted">DO YOU
                                                    EXPERIENCE ANY OF THE
                                                    FOLLOWING?</label>
                                                @php
                                                    $_datas = ['Difficulty in Breathing', 'Travelled during the last 15 days', 'Direct Contact with a COVID-19 Patient', 'Direct Contact with PUI / PUM', 'None of the Above'];
                                                @endphp
                                                <div class="form-group clearfix">
                                                    @foreach ($_datas as $_key => $_data)
                                                        @php
                                                            $_status = '';
                                                            if (old('question2')) {
                                                                foreach (old('question2') as $ans) {
                                                                    if ($_data == $ans) {
                                                                        $_status = 'checked';
                                                                    }
                                                                }
                                                            }
                                                            
                                                        @endphp
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox"
                                                                id="checkboxSuccess2{{ $_key }}"
                                                                name="question2[]" value="{{ $_data }}"
                                                                {{ $_status }}>
                                                            <label for="checkboxSuccess2{{ $_key }}"
                                                                class="text-muted">
                                                                {{ $_data }}
                                                            </label>
                                                        </div>
                                                        <br>
                                                    @endforeach

                                                </div>
                                                @error('question2')
                                                    <small class="text-danger h6"><b>
                                                            {{ $message }}</b></small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="" class="text-muted">Have you tested
                                                    COVID-19
                                                    positive in the last
                                                    15 days?</label>
                                                <div class="form-group clearfix">
                                                    <div class="icheck-success d-inline">
                                                        <input type="radio" name="question3" id="radioSuccess1"
                                                            value="YES"
                                                            {{ old('question3') == 'YES' ? 'checked' : '' }}>
                                                        <label for="radioSuccess1" class="text-muted">YES
                                                        </label>
                                                    </div>
                                                    <br>
                                                    <div class="icheck-success d-inline">
                                                        <input type="radio" name="question3" id="radioSuccess2"
                                                            value="NO"
                                                            {{ old('question3') == 'NO' ? 'checked' : '' }}>
                                                        <label for="radioSuccess2" class="text-muted">
                                                            NO
                                                        </label>
                                                    </div>
                                                </div>
                                                @error('question3')
                                                    <small class="text-danger h6"><b>
                                                            {{ $message }}</b></small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- /.col -->
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </form> --}}
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">

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
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>
    <script src="{{ asset('/js/instascan.min.js') }}"></script>
    <script type="text/javascript">
        setInterval(() => {
            let currentDate = new Date();
            $('.real-time').text(currentDate)
            /*   table_data(); */
        }, 500);

        var scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            scanPeriod: 5,
            mirror: true
        });
        scanner.addListener('scan', function(content) {
            scan_qr_code(content)
            table_data()
            //window.location.href=content;
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
            alert(e);
        });

        function table_data() {
            $.get('/executive/fetch-attendance', function(respond) {
                $('.table-body-100').empty()
                console.log(respond);

                respond._data.forEach(data => {
                    /* 
                                        var time_in_timestamp = Date.parse(data.time_in)
                                        var time_in = new Date(time_in_timestamp*1000); */
                    var time_out = data.time_out != null ? data.time_out : '-';
                    $('.table-body-100').append(
                        "<tr>" +
                        "<td>" + data.first_name + " " + data.last_name + "</td>" +
                        "<td>" + data.time_in + "</td>" +
                        "<td>" + time_out + "</td>" +

                        "</tr>"
                    );
                    console.log(data);
                });


            });
        }

        function scan_qr_code(_data) {
            $.get('scan-code/' + _data, function(data) {
                $('.full-name').text(data._data.last_name + "" + data._data.first_name)
                $('.full-name').val(data._data.last_name + "" + data._data.first_name)
                $('.employee').val(data._data.id)
                console.log(data._status.respond)
                if (data._status.respond == 'time-in') {
                    toastr.success('Welcome ' + data._data.first_name, 'Time In!')
                } else {
                    toastr.success('Ingat ' + data._data.first_name, 'Time Out!')
                }

            }).fail(function() {
                toastr.error('Invalid QR Code.', 'Error!')
                $('.full-name').text('')
                $('.full-name').val('')
                $('.employee').val('')
            });
        }
    </script>
</body>

</html>
