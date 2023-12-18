@extends('widgets.report.main-report-template')
@section('title-report', 'DAILY ATTENDANCE REPORT')
@section('form-code', '')
@section('content')

    @foreach ($employees as $employee)
        <div class="page-content">
            <h3 class="text-center"><b>DAILY TIME RECORD</b></h3>
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
            <table class="table-outline-2 table-student-content" style="margin-top: 3%">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>TIME-IN</th>
                        <th>TIME-OUT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dateList as $_date)
                        <tr>
                            <td>{{ date('F d, Y', strtotime($_date)) }}</td>
                            <td>
                                @if ($employee->date_attendance($_date))
                                    {{ date_format(date_create($employee->date_attendance($_date)->time_in), 'h:i:s a') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($employee->date_attendance($_date))
                                    @if ($employee->date_attendance($_date)->time_out)
                                        {{ date_format(date_create($employee->date_attendance($_date)->time_out), 'h:i:s a') }}
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
        </div>
        <div class="page-break"></div>
    @endforeach

@endsection
