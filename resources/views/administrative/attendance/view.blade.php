@extends('app')
@section('page-title', 'Employee Attendance')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Attendance</li>

    </ol>
@endsection
@section('page-content')
    <div class="card">
        <div class="card-body">
            <div class="text-success h4"><b>Generate Attendane Report</b></div>
            <form action="/administrative/attendance/report" method="get">
                <div class="row">

                    <div class="form-group col-md-5">
                        <label for="" class="text-muted">Start Date</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="" class="text-muted">End Date</label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>
                    <input type="hidden" name="r_view" value="weekly">
                    <div class="form-group col-md-2">
                        <a href="/administrative/attendance/report?r_view=daily" class="btn btn-info btn-block"> <i
                                class="fa fa-print"></i> Daily Attendance</a>
                        {{-- <a href="/administrative/attendance/report?r_view=weekly" class="btn btn-info btn-block"> <i
                            class="fa fa-print"></i> GENERATE </a> --}}
                        <button class="btn btn-success btn-block"> <i class="fa fa-print"></i> Generate</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><span class="text-muted"><b>ATTENDANCE MONITORING</b></span></h3>
            {{-- <div class="card-tools">
            
        </div> --}}
        </div>
        <div class="card-body p-0">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr class="text-center">
                        <th>EMPLOYEE</th>
                        <th>TIME IN</th>
                        <th>TIME OUT</th>
                        {{-- <th>HEALTH</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($_employees as $_data)
                        <tr>
                            <td style=" width:70px" class="">
                                <span class="text-info h5">
                                    <b> {{ strtoupper($_data->first_name . ' ' . $_data->last_name) }}</b><br>

                                </span>
                                <span class="text-muted ">
                                    <small> <b>{{ $_data->department }}</b></small>
                                </span>
                            </td>
                            <td class="text-center">


                                @if ($_data->staff_id)
                                    @if ($_data->daily_attendance)
                                        <span class="h4 text-info">
                                            {{ date_format(date_create($_data->daily_attendance->time_in), 'h:i:s a') }}
                                        </span>
                                    @else
                                        <span class="h4 text-info">
                                            -
                                        </span>
                                    @endif
                                @else
                                    <span class="h4 text-info">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($_data->daily_attendance)
                                    <span class="h4 text-info">
                                        @if ($_data->daily_attendance->time_out)
                                            {{ date_format(date_create($_data->daily_attendance->time_out), 'h:i:s a') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
    </div>
@endsection
