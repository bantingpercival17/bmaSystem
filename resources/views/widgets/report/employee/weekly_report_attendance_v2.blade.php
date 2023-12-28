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
                        <th>DATE</th>
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
                            $time_in = $employee->daily_time_in($date);
                            $time_out = $employee->daily_time_out($date);
                            $timeInDisplay = $time_in ? date_format(date_create($time_in->time_in), 'h:i:s a') : '-';
                            $timeOutDisplay = $time_out ? ($time_out->time_out ? date_format(date_create($time_out->time_out), 'h:i:s a') : 'NO TIME OUT') : '-';
                            $late = $timeInDisplay != '-' ? $employee->compute_late_per_day(date_format(date_create($time_in->time_in), 'H:i:s')) : '-';
                            $tardines = $timeOutDisplay != '-' ? ($time_out->time_out ? $employee->compute_tardines_per_day(date_format(date_create($time_out->time_out), 'H:i:s')) : 'NO TIME OUT') : '-';

                        @endphp
                        <tr class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                            <td>{{ date('F d, Y', strtotime($date)) }}</td>
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
            <div class="page-break"></div>
        @endforeach
    </div>
@endsection
