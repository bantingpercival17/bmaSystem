@extends('layouts.app-main')
@section('page-title', 'Log-in')
@section('page-content')

    {{-- <div class="hold-transition login-page">
        <div class=" container row">
            <div class="col-md-7 col-xs-12 text-center d-flex align-items-center justify-content-center">
                <div class="">
                    <img class="img-circle" src="{{ asset('assets/img/bma-logo.jpg') }}" alt="AdminLTELogo"
                        height="150" width="150">
                    <h2 style='font-family: "Times New Roman", Times, serif;'>BALIWAG MARITIME ACADEMY, INC</h2>

                </div>
            </div>
            <div class="col-md-5 col-xs-12  align-items-center justify-content-center">
                <div class="card">
                    <div class="card-body ">

                        <form action="/login" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email" name="email"
                                    value="{{ old('email') }}" required autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" name="password"
                                    required autocomplete="current-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember" name="remember">
                                        <label for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div> --}}
    <div class="wrapper">
        <div class="res-hide row m-0 align-items-center vh-100">

            <div class="col-lg-7 d-md-block d-none p-0">
                <img src="{{ asset('resources\image/BMA BUILDING.gif') }}" class="img-fluid gradient-main vh-100"
                    alt="images">
            </div>
            <div class="col-lg-5 pb-0">
                <div class="card-body auth-padding">
                    <center>
                        <img src="{{ asset('assets/image/bma-logo-1.png') }}"
                            class="center img-fluid avatar avatar-100 rounded-circle" alt="images">

                    </center>
                    <h2 class="mb-2 text-center"><b>BMA PORTAL</b></h2>
                    <p class="text-center">SIGN IN</p>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control border-primary" id="email"
                                        aria-describedby="email" name="email">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control border-primary" id="password"
                                        aria-describedby="password" name="password">
                                </div>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-between">
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="customCheck1" name="remember">
                                    <label class="form-check-label" for="customCheck1">Remember Me</label>
                                </div>
                                <a href="recoverpw.html">Forgot Password?</a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
