@extends('app')
@section('page-title', 'Profile')
@section('css')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endsection
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/administrator/accounts">Accounts</a></li>
        <li class="breadcrumb-item active">Profile</li>

    </ol>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-9">
            <div class="callout callout-success">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-xs-12 text-center">
                            <img class="img-circle elevation-2" src="http://20.0.0.120:90/img/midship-man.jpg"
                                alt="User Avatar" height="120px">

                        </div>
                        <div class="col-md-9 col-xs-12">
                            <h5><b class="text-muted">EMPLOYEE'S INFORMATION</b></h5>
                            <h4 class="text-info">
                                <b>{{ strtoupper(trim($_staff->first_name . ' ' . $_staff->last_name)) }}</b>
                            </h4>
                            <div class="row">
                                <div class="col-md">
                                    <small class="text-muted"><b>JOB DESCRIPTION</b></small><br>
                                    <span class="h5 text-info"><b>{{ strtoupper($_staff->job_description) }}</b></span>
                                </div>
                                <div class="col-md">
                                    <small class="text-muted"><b>DEPARTMENT</b></small><br>
                                    <span class="h5 text-info"><b>{{ strtoupper($_staff->department) }}</b></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
