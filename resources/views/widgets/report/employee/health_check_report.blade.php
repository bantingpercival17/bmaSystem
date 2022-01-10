@extends('widgets.report.report_layout')
@section('title-report', 'HEALTH CHECK MONITORING')
@section('form-code', 'HCM')
@section('content')
    <div class="content-1">
        <h3 class="text-center"><b>HEALTH CHECK MONITORING</b></h3>
        <p>Date:
            <b><u>{{ request()->input('_date') ? date_format(date_create(request()->input('_date')), 'M d,Y') : now()->format('M d,Y') }}</u></b>
        </p>

        <table class="attendance-table">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th width="100">TEMPERATURE</th>
                    <th>MEDICAL STATUS</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($_employees as $_employee)
                    <tr>
                        <td>{{ strtoupper($_employee->first_name . ' ' . $_employee->last_name) }}</td>
                        <td>
                            @php
                                $_description = $_employee->daily_attendance_report ? json_decode($_employee->daily_attendance_report->description) : '-';
                                //echo var_dump($_description);
                            @endphp
                            {{ $_employee->daily_attendance_report ? $_description->body_temprature : $_description }}
                        </td>
                        <td>
                            @if ($_employee->daily_attendance_report)
                                @foreach ($_description as $key => $item)
                                    @if ($key != 'body_temprature' && $key != 'gatekeeper_in')
                                        <label><b>{{ ucwords(str_replace('_', ' ', $key)) }}</b></label> <br>
                                        @if (is_array($item))
                                            @foreach ($item as $_item)
                                                <span>-{{ $_item }}</span>
                                                <br>
                                            @endforeach
                                        @else
                                            <span>-{{ $item }}</span>
                                        @endif
                                    @endif
                                @endforeach
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
