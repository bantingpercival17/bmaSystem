<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Monitoring | Baliwag Maritime Academy, Inc.</title>

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
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="{{ asset('assets/image/bma-logo-1.png') }}" class="navbar-brand">
                    <img src="{{ asset('assets/image/bma-logo-1.png') }}" alt="AdminLTE Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">Baliwag Maritime Academy, Inc.</span>
                </a>
            </div>
        </nav>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-success font-weight-light"><b>| Attendance & Health Monitoring</b> </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container">
                    <span class="text-muted h3"><b>DATE AND TIME: </b></span> <span class="h2 text-success"> <b>{{ date('M d,Y h:m:s a') }}</b></span>
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">

                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                        href="#custom-tabs-four-profile" role="tab"
                                        aria-controls="custom-tabs-four-profile" aria-selected="true">Time In</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill"
                                        href="#custom-tabs-four-messages" role="tab"
                                        aria-controls="custom-tabs-four-messages" aria-selected="false">Time Out</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill"
                                        href="#custom-tabs-four-settings" role="tab"
                                        aria-controls="custom-tabs-four-settings" aria-selected="false">Work Form
                                        Home</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade active show" id="custom-tabs-four-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-profile-tab">
                                    <div class="row">
                                        <div
                                            class="col-md-5 col-xs-12 text-center d-flex align-items-center justify-content-center">
                                            <div class="">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div style="width:auto" id="reader"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 col-xs-12  align-items-center justify-content-center">
                                            <div class="card">
                                                <div class="card-body ">
                                                    <form action="/attendance" method="post">
                                                        @csrf
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
                                                                    <input type="hidden" name="employee"
                                                                        class="employee"
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
                                                                    <input type="text" class="form-control"
                                                                        name="body_temp"
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
                                                                                    name="question1[]"
                                                                                    value="{{ $_data }}"
                                                                                    {{ $_status }}>
                                                                                <label
                                                                                    for="checkboxSuccess{{ $_key }}"
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
                                                                                    name="question2[]"
                                                                                    value="{{ $_data }}"
                                                                                    {{ $_status }}>
                                                                                <label
                                                                                    for="checkboxSuccess2{{ $_key }}"
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
                                                                            <input type="radio" name="question3"
                                                                                id="radioSuccess1" value="YES"
                                                                                {{ old('question3') == 'YES' ? 'checked' : '' }}>
                                                                            <label for="radioSuccess1"
                                                                                class="text-muted">YES
                                                                            </label>
                                                                        </div>
                                                                        <br>
                                                                        <div class="icheck-success d-inline">
                                                                            <input type="radio" name="question3"
                                                                                id="radioSuccess2" value="NO"
                                                                                {{ old('question3') == 'NO' ? 'checked' : '' }}>
                                                                            <label for="radioSuccess2"
                                                                                class="text-muted">
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
                                                                <button type="submit"
                                                                    class="btn btn-primary btn-block">SUBMIT</button>
                                                            </div>
                                                            <!-- /.col -->
                                                        </div>
                                                    </form>


                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-messages-tab">
                                    <div class="row">
                                        <div
                                            class="col-md-5 col-xs-12 text-center d-flex align-items-center justify-content-center">
                                            <div class="">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div style="width:auto" id="reader-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 col-xs-12  align-items-center justify-content-center">
                                            <div class="card">
                                                <div class="card-body ">
                                                    <form action="/attendance" method="post">
                                                        @csrf
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
                                                                    <input type="hidden" name="employee"
                                                                        class="employee"
                                                                        value="{{ old('employee') }}">
                                                                    @error('employee')
                                                                        <small class="text-danger h6"><b>
                                                                                {{ $message }}</b></small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                           
                                                        </div>
                                                    </form>


                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-settings-tab">
                                    Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis
                                    tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque
                                    tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum
                                    consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra.
                                    Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut
                                    nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet
                                    accumsan ex sit amet facilisis.
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
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
    <script src="{{ asset('/js/html5-qrcode.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        @if (Session::has('message'))
            toastr.success("{{ session('message') }}")
        @endif
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 5,
                qrbox: 250
            });
        var html5QrcodeScanner_1 = new Html5QrcodeScanner(
            "reader-1", {
                fps: 5,
                qrbox: 250
            });

        function onScanSuccess(decodedText, decodedResult) {
            let _data = decodedText
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


        html5QrcodeScanner.render(onScanSuccess);
        html5QrcodeScanner_1.render(onScanSuccess);
    </script>
</body>

</html>
