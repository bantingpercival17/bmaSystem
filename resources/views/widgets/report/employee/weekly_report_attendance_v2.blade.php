@extends('widgets.report.main-report-template')
@section('title-report', 'EMPLOYEE ATTENDANCE REPORT')
@section('form-code', '')
@section('content')
    <div class="page-content">
        @foreach ($employees as $employee)
            <div class="summary-grade-header">
                <h2 class="text-center" style="margin:0px;">
                    <b>DAILY TIME RECORD</b>
                </h2>
            </div>
            <table class="table-content">
                <tbody>
                    <tr>
                        <td><small>EMPLOYEE NAME :</small>
                            <span><b>{{ strtoupper($employee->first_name . ' ' . $employee->last_name) }}</b></span>
                        </td>
                        <td>
                            <small>DEPARTMENT: </small>
                            <span><b>{{ $employee->department }}</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td><small>DATE:</small>
                            <span><b>{{ date('F d, Y', strtotime($start_date)) . ' TO ' . date('F d, Y', strtotime($end_date)) }}</b></span>
                        </td>

                        <td>
                            <small>JOB DESCRIPTION: </small>
                            <span><b>{{ $employee->job_description }}</b></span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table-outline-2 table-student-content" style="margin-top: 2%">
                @php
                    $contentNumber = 0;
                    $contentCount = 45;
                @endphp
                <thead>
                    <tr>
                        <th rowspan="2">DATE</th>
                        <th colspan="2">AM</th>
                        <th colspan="2">PM</th>
                        <th colspan="2">LATE / UNDER-TIME</th>
                    </tr>
                    <tr>
                        <th>TIME-IN</th>
                        <th>TIME-OUT</th>
                        <th>TIME-IN</th>
                        <th>TIME-OUT</th>
                        <th>TARDINES</th>
                        <th>UNDER-TIME</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dateList as $date)
                        @php
                            $contentNumber += 1;
                            $am_time = $employee->daily_time_am($date);
                            $pm_time = $employee->daily_time_pm($date);
                            //$time_in_am = date_format(date_create($am_time->time_in), 'h:i:s a');
                            $time_in_am = $am_time
                                ? ($am_time->time_in
                                    ? date_format(date_create($am_time->time_in), 'h:i:s a')
                                    : '-')
                                : '-';

                            $time_out_am = $am_time
                                ? ($am_time->time_out
                                    ? date_format(date_create($am_time->time_out), 'h:i:s a')
                                    : '-')
                                : '-';

                            $time_in_pm = $pm_time
                                ? ($pm_time->time_in
                                    ? date_format(date_create($pm_time->time_in), 'h:i:s a')
                                    : '-')
                                : '-';

                            $time_out_pm = $pm_time
                                ? ($pm_time->time_out
                                    ? date_format(date_create($pm_time->time_out), 'h:i:s a')
                                    : '-')
                                : '-';

                            $late =
                                $time_in_am != '-'
                                    ? $employee->compute_late_per_day(
                                        date_format(date_create($am_time->time_in), 'H:i:s'),
                                    )
                                    : '-';
                            $tardines =
                                $time_out_pm != '-'
                                    ? ($pm_time->time_out
                                        ? $employee->compute_tardines_per_day(
                                            date_format(date_create($pm_time->time_out), 'H:i:s'),
                                        )
                                        : 'NO TIME OUT')
                                    : '-';

                        @endphp
                        <tr class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                            <td>{{ date('F d, Y', strtotime($date)) }}</td>
                            <td>
                                {{ $time_in_am }}
                            </td>
                            <td>
                                {{ $time_out_am }}
                            </td>
                            @if (count($employee->date_attendance_list($date)->get()) > 1)
                                <td>
                                    {{ $time_in_pm }}
                                </td>
                                <td>
                                    {{ $time_out_pm }}
                                </td>
                            @else
                                <td></td>
                                <td></td>
                            @endif

                            <td>
                                {{ $late }}
                            </td>
                            <td>
                                {{ $tardines }}
                                {{--  {{ count($employee->date_attendance_list($date)->get()) }} --}}
                            </td>
                        </tr>
                        @if ($contentNumber >= $contentCount)
                            @php
                                $contentNumber = 0;
                            @endphp
                        @endif
                    @endforeach

                </tbody>
            </table>
            <div class="page-break"></div>
        @endforeach
    </div>
@endsection
