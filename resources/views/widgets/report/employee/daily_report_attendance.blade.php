@extends('widgets.report.report_layout')
@section('title-report', 'DAILY ATTENDANCE REPORT')
@section('form-code', 'ACC-14 ')
@section('content')
    <div class="content-1">
        <h3 class="text-center"><b>DAILY ATTENDANCE REPORT</b></h3>
        <p>Date:
            <b><u>{{ request()->input('_date') ? date_format(date_create(request()->input('_date')), 'M d,Y') : now()->format('M d,Y') }}</u></b>
        </p>

        <table class="attendance-table">
            <thead>
                <tr>
                    <th>DEPARTMENT</th>
                    <th>NAME</th>
                    <th width="100">TIME-IN</th>
                    <th width="100">TIME-OUT</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($_employees as $_employee)
                    <tr>
                        <td>{{ strtoupper($_employee->first_name . ' ' . $_employee->last_name) }}</td>
                        <td>{{ strtoupper($_employee->department) }}</td>
                        <td>
                            @if ($_employee->daily_attendance_report)
                                {{ date_format(date_create($_employee->daily_attendance_report->time_in), 'h:i:s a') }}
                            @else
                                -
                            @endif

                        </td>
                        <td>
                            @if ($_employee->daily_attendance_report)
                                @if ($_employee->daily_attendance_report->time_out)
                                    {{ date_format(date_create($_employee->daily_attendance_report->time_out), 'h:i:s a') }}
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
        <br>


    </div>
@endsection
