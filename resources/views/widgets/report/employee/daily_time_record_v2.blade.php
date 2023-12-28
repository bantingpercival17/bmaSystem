@extends('widgets.report.main-report-template')
@section('title-report', 'DAILY ATTENDANCE REPORT')
@section('form-code', 'ACC-14 ')
@section('content')

    @foreach ($dateList as $date)
        <div class="page-content">
            <div class="content">
                <div class="content-header">
                    <h3 class="fw-bolder">DAILY ATTENDANCE REPORT</h3>
                    <small class="fw-bolder">{{ date_format(date_create($date), 'M d,Y') }}</small>
                </div>
                <table class="table-outline-2 table-student-content" style="margin-top: 2%">
                    @php
                        $contentNumber = 0;
                        $contentCount = 45;
                    @endphp
                    <thead>
                        <tr>
                            <th>EMPLOYEE NAME</th>
                            <th>TIME-IN</th>
                            <th>TIME-OUT</th>
                            <th>TARDINES</th>
                            <th>UNDER-TIME</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            @php
                                $contentNumber += 1;
                                $time_in = $employee->daily_time_in($date);
                                $time_out = $employee->daily_time_out($date);
                                $timeInDisplay = $time_in ? date_format(date_create($time_in->time_in), 'h:i:s a') : '-';
                                $timeOutDisplay = $time_out ? ($time_out->time_out ? date_format(date_create($time_out->time_out), 'h:i:s a') : 'NO TIME OUT') : '-';
                                $late = $timeInDisplay != '-' ? $employee->compute_late_per_day(date_format(date_create($time_in->time_in), 'H:i:s')) : '-';
                                $tardines = $timeOutDisplay != '-' ? ($time_out->time_out ? $employee->compute_tardines_per_day(date_format(date_create($time_out->time_out), 'H:i:s')) : 'NO TIME OUT') : '-';
                            @endphp
                            <tr class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                                <td>{{ strtoupper($employee->first_name . ' ' . $employee->last_name) }}</td>
                                <td>
                                    {{ $timeInDisplay }}
                                </td>
                                <td>
                                    {{ $timeOutDisplay }}
                                </td>
                                <td>
                                    {{ $late }}
                                </td>
                                <td>
                                    {{ $tardines }}
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

            </div>
        </div>
        <div class="page-break"></div>
    @endforeach

    {{--  <div class="content">
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


    </div> --}}
@endsection
