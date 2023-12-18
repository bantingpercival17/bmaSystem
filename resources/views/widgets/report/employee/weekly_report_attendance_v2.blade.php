@extends('widgets.report.main-report-template')
@section('title-report', 'DAILY ATTENDANCE REPORT')
@section('form-code', '')
@section('content')

    @foreach ($employees as $employee)
        <div class="page-content">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dateList as $date)
                        @php
                            $contentNumber += 1;
                        @endphp
                        <tr class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                            <td>{{ date('F d, Y', strtotime($date)) }}</td>
                            <td>
                                @if ($employee->date_attendance($date))
                                    {{-- {{ $employee->date_attendance($date)->time_in }} --}}
                                    {{ date_format(date_create($employee->date_attendance($date)->time_in), 'h:i:s a') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($employee->date_attendance($date))
                                    @if ($employee->date_attendance($date)->time_out)
                                        {{ date_format(date_create($employee->date_attendance($date)->time_out), 'h:i:s a') }}
                                    @else
                                        -
                                    @endif
                                @else
                                    -
                                @endif
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
        <div class="page-break"></div>
    @endforeach

@endsection
