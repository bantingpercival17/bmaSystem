@extends('widgets.report.app_report_template')
@section('title-report', 'DAILY ATTENDANCE REPORT')
@section('form-code', 'ACC-14 ')
@section('content')
    <div class="content">
        {{--  <h3 class="text-center"><b>DAILY ATTENDANCE REPORT</b></h3>
        <p>Date:
            <b><u>{{ request()->input('_date') ? date_format(date_create(request()->input('_date')), 'M d,Y') : now()->format('M d,Y') }}</u></b>
        </p> --}}

        <table class="table-2">
            <thead>
                <tr>
                    <th>QR CODE</th>
                    <th>NAME</th>
                    <th></th>

                    {{--  <th>DEPARTMENT</th> --}}
                    {{-- <th width="100">TIME-IN</th>
                    <th width="100">TIME-OUT</th> --}}

                </tr>
            </thead>
            <tbody>
                @foreach ($_employees as $_employee)
                    <tr>
                        <td class="text-center" style="width: 25%; hiegth:100px;">
                            <br><br><br><br>
                            <img src="data:image/png;base64, {!! base64_encode(
                                QrCode::style('round', 0.5)->eye('square')->size(170)->generate('employee:' . $_employee->user->email),
                            ) !!} ">
                            <br><br>
                            <label for="" style="font-size: 14px">
                                {{ ucwords($_employee->first_name . ' ' . $_employee->last_name) }}
                            </label>
                            <br>
                            <label for="" style="font-size: 9px">
                                {{ ucwords($_employee->department) }}
                            </label>
                            <br><br>
                        </td>
                        <td style="width: 25%;text-align:center;">
                            <img src="{{ public_path() . '/assets/image/bma-logo-1.png' }}" alt="" width="150">
                            <br> bma.edu.ph
                        </td>
                        <td class="text-center" style="width: 30%; hiegth:100px;">
                            {{--  <br><br>
                            <img src="data:image/png;base64, {!! base64_encode(
                                QrCode::style('round', 0.5)->eye('square')->size(170)->generate('employee:' . $_employee->user->email),
                            ) !!} ">
                            <br>
                            <label for="">
                                {{ ucwords($_employee->first_name . ' ' . $_employee->last_name) }}
                            </label> --}}

                        </td>
                        {{--  <td style="width: 30%;text-align:center;">
                            <img src="{{ public_path() . '/assets/image/bma-logo-1.png' }}" alt="" width="150">
                            <br> bma.edu.ph
                        </td> --}}
                        {{-- <td>
                            {{ strtoupper($_employee->first_name . ' ' . $_employee->last_name) }}
                            <br>{{ strtoupper($_employee->department) }}
                        </td> --}}
                        {{-- <td>
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
                        </td> --}}
                    </tr>
                @endforeach

            </tbody>
        </table>
        <br>


    </div>
@endsection
