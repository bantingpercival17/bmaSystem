{{-- @extends('app')
@section('page-title', 'Attendances')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.min.css') }}">
@endsection
@section('js')
    <!-- BS-Stepper -->
    <script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script>
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>
@endsection
@section('page-content')
    <div class="container">

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body text-center">
                        {!! QrCode::size(300)->generate($_data) !!}
                        <label for="">11-22-33</label>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
            </div>
        </div>

    </div>
@endsection --}}

@extends('layouts.app-main')
@section('page-title', 'Attendances')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('employee.attendance') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Employee Attendance</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">BMA QRCODE</li>
@endsection
@section('page-content')
    <div class="card text-center">
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <label for="" class="text-center">
                        {!! QrCode::size(300)->generate($_data) !!}
                    </label>

                </div>
                <div class="col-md">
                    <p>
                        <label for="">Valid Qr Code Date: <br> <b>{{ now() }}</b></label>
                    </p>
                    <div class="form-group">
                        <form action="{{ route('employee.work-from-home') }}" method="post">
                            @csrf
                            <input type="hidden" name="_data" value="{{ $_data }}">
                            <button type="submit" class="btn btn-info text-white w-100 mt-2">I'm Work from Home</button>
                        </form>
                        <form action="{{ route('employee.download-qrcode') }}" method="post">
                            @csrf
                            <input type="hidden" name="_data" value="{{ $_data }}">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Download BMA QR CODE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
