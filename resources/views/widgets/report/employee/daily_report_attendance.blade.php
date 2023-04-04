@extends('widgets.report.app_report_template')
@section('title-report', 'DAILY ATTENDANCE REPORT')
@section('form-code', 'ACC-14 ')
@section('content')
    <div class="content">
        <h3 class="text-center"><b>DAILY ATTENDANCE REPORT</b></h3>
        <p>Date:
            <b><u>{{ request()->input('_date') ? date_format(date_create(request()->input('_date')), 'M d,Y') : now()->format('M d,Y') }}</u></b>
        </p>

        <table class="table-2">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>DEPARTMENT</th>
                    <th width="100">TIME-IN</th>
                    <th width="100">TIME-OUT</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($_employees as $_employee)
                    <tr>
                        <td style="width: 40%; hiegth:100px; ">
                            <label for="" style="font-size: 14px;font-weight:bolder">
                                {{ ucwords($_employee->first_name . ' ' . $_employee->last_name) }}
                            </label>
                        </td>
                        <td>
                            <br>{{ strtoupper($_employee->department) }}
                        </td>
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
