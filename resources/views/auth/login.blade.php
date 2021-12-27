@extends('app')
@section('page-title', 'Log-in')
@section('page-content')

    <div class="hold-transition login-page">
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

    </div>
@endsection
