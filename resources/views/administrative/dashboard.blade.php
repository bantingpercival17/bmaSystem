@extends('app')
@section('page-title', 'Dashboard')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item active">Dashboard</li>

    </ol>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <a href="/employee/attendance">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-md"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text"><b class="text-muted">
                            Daily Health Check</b></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>

            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <a href="/administrative/attendance">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clipboard-list"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text"><b class="text-muted">
                                Attendance
                            </b></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </a>
        </div>

    </div>
@endsection
