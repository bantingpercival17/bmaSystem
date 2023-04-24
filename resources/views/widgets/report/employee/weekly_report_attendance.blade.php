@extends('widgets.report.report_layout')
@section('title-report', 'DAILY ATTENDANCE REPORT')
@section('form-code', '')
@section('content')
    <div class="content-1">
        @foreach ($_employees as $_employee)
            <h3 class="text-center"><b>DAILY TIME RECORD</b></h3>
            <table class="table">
                <tbody>
                    <tr>
                        <td><small>EMPLOYEE NAME :</small>
                            <span><b>{{ strtoupper($_employee->first_name . ' ' . $_employee->last_name) }}</b></span>
                        </td>
                        <td><small>DATE CUT-OFF:</small>
                            <span><b></b></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small>DEPARTMENT: </small>
                            <span><b>{{ $_employee->department }}</b></span>
                        </td>
                        <td>
                            <small>JOB DESCRIPTION: </small>
                            <span><b>{{ $_employee->job_description }}</b></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table-content">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>TIME-IN</th>
                        <th>TIME-OUT</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($_dates as $_date)
                        <tr>
                            <td>{{ date('F d, Y', strtotime($_date)) }}</td>
                            <td>
                                @if ($_employee->date_attendance($_date))
                                    {{ date_format(date_create($_employee->date_attendance($_date)->time_in), 'h:i:s a') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($_employee->date_attendance($_date))
                                    @if ($_employee->date_attendance($_date)->time_out)
                                        {{ date_format(date_create($_employee->date_attendance($_date)->time_out), 'h:i:s a') }}
                                    @else
                                        -
                                    @endif

                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="page-break"></div>
        @endforeach

        <p>Date: <b><u></u></b></p>


    </div>
@endsection
