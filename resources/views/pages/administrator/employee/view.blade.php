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
        <div class="col-lg-9 col-md-12">
            <div class="callout callout-success">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-xs-12 text-center">
                            @php
                                if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg'))) {
                                    $_image = strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg';
                                } else {
                                    $_image = 'avatar.png';
                                }
                            @endphp
                            <img class="img-circle elevation-2" src="{{ asset('/assets/img/staff/' . $_image) }}"
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
        <div class="col-lg-3 col-md-4">
            <div class="card">
                <div class="card-header">
                    <label for="" class="text-muted"><b>ADD ROLES</b></label>
                    <div class="card-tools">
                        <form action="/administrator/accounts/role" method="post">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="hidden" name="id" value="{{ $_staff->user->id }}">
                                <div class="custom-file">
                                    <select name="_role" id="" class="form-control">
                                        @foreach ($_roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-info btn-sm" type="submit"><i
                                            class="fas fa-plus"></i></button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
                <div class="card-body">
                    @foreach ($_staff->user->roles as $item)
                        <span class="badge badge-info"> {{ $item->display_name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
