@extends('widgets.report.main-report-template')
@section('title-report', 'SUMMARY OF LATE AND UNDERTIME')
@section('form-code', 'ACC-14-C ')
@section('content')
    <div class="page-content">
        <div class="content-header">
            <h3 class="fw-bolder">SUMMARY OF LATE AND UNDERTIME</h3>
            <small class="fw-bolder">{{ strtoupper(date_format(date_create($date), 'F Y')) }}</small>
        </div>
        <table class="table-outline-2 table-student-content" style="margin-top: 2%">
            @php
                $contentNumber = 0;
                $contentCount = 45;
            @endphp
            <thead>
                <tr>
                    <th rowspan="2">EMPLOYEE NAME</th>
                    <th colspan="3">(15TH)</th>
                    <th colspan="3">(30TH)</th>
                </tr>
                <tr>
                    <th>TARDINES</th>
                    <th>UNDER-TIME</th>
                    <th>TOTAL (15TH)</th>
                    <th>TARDINES</th>
                    <th>UNDER-TIME</th>
                    <th>TOTAL (30TH)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    @php
                        $contentNumber += 1;
                    @endphp
                    <tr class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                        <td>{{ strtoupper($employee->last_name . ', ' . $employee->first_name) }}</td>
                        <td>{{ $employee->attendance_summary_tardiness($date, true) ?: '-' }}
                        </td>
                        <td>{{ $employee->attendance_summary_undertime($date, true) ?: '-' }}</td>
                        <td></td>
                        <td>{{ $employee->attendance_summary_tardiness($date, false) ?: '-' }}</td>
                        <td>{{ $employee->attendance_summary_undertime($date, false) ?: '-' }}</td>
                        <td></td>
                    </tr>
                    @if ($contentNumber >= $contentCount)
                        @php
                            $contentNumber = 0;
                        @endphp
                    @endif
                @endforeach

                {{--    @foreach ($employees as $employee)
                    @php
                        $contentNumber += 1;
                        $time_in = $employee->daily_time_in($date);
                        $time_out = $employee->daily_time_out($date);
                        $timeInDisplay = $time_in ? date_format(date_create($time_in->time_in), 'h:i:s a') : '-';
                        $timeOutDisplay = $time_out
                            ? ($time_out->time_out
                                ? date_format(date_create($time_out->time_out), 'h:i:s a')
                                : 'NO TIME OUT')
                            : '-';
                        $late =
                            $timeInDisplay != '-'
                                ? $employee->compute_late_per_day(
                                    date_format(date_create($time_in->time_in), 'H:i:s'),
                                )
                                : '-';
                        $tardines =
                            $timeOutDisplay != '-'
                                ? ($time_out->time_out
                                    ? $employee->compute_tardines_per_day(
                                        date_format(date_create($time_out->time_out), 'H:i:s'),
                                    )
                                    : 'NO TIME OUT')
                                : '-';
                    @endphp
                    <tr class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                        <td></td>
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

                @endforeach --}}

            </tbody>
        </table>
    </div>
@endsection
