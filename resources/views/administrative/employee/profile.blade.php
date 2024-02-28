@extends('app')
@section('page-title', 'Profile')
@section('css')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
@endsection
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/administrative/employees">Employees</a></li>
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
                            <img class="img-circle elevation-2" src="{{ $_staff->profile_picture() }}" alt="User Avatar"
                                height="120px">
                        </div>
                        <div class="col-md-9 col-xs-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5><b class="text-muted">EMPLOYEE'S INFORMATION</b></h5>
                                    <h4 class="text-info">
                                        <b>{{ strtoupper(trim($_staff->first_name . ' ' . $_staff->last_name)) }}</b>
                                    </h4>

                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <small class="text-muted">TIME IN : </small>
                                            @if ($_staff->daily_attendance)
                                                <span class="h6 text-info">
                                                    {{ date_format(date_create($_staff->daily_attendance->time_in), 'h:i:s a') }}
                                                </span>
                                            @else
                                                <span class="h4 text-info">
                                                    -
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <small class="text-muted">TIME OUT : </small>
                                            @if ($_staff->daily_attendance)
                                                <span class="h6 text-info">
                                                    @if ($_staff->daily_attendance->time_out)
                                                        {{ date_format(date_create($_staff->daily_attendance->time_out), 'h:i:s a') }}
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                            @else
                                                <span class="h4 text-info">
                                                    -
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><span class="text-muted"><b>ATTENDANCE MONITORING</b></span></h3>
                </div>
                <div class="card-body p-0">

                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>TIME IN</th>
                                <th>TIME OUT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($_staff->attendance_list->count() > 0)
                                @foreach ($_staff->attendance_list as $_data)
                                    <tr>
                                        <td>
                                            {{ date_format(date_create($_data->time_in), 'F d,y') }}
                                        </td>
                                        <td>
                                            @if ($_data)
                                                <span class="h6 text-info">
                                                    {{ date_format(date_create($_data->time_in), 'h:i:s a') }}
                                                </span>
                                            @else
                                                <span class="h6 text-info">
                                                    -
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($_data)
                                                <span class="h6 text-info">
                                                    @if ($_data->time_out)
                                                        {{ date_format(date_create($_data->time_out), 'h:i:s a') }}
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                            @else
                                                <span class="h6 text-info">
                                                    -
                                                </span>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">No Data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4">

        </div>
    </div>
@endsection
