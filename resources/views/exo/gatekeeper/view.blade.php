@extends('app')
@section('page-title', 'Attendance - ' . now()->format('M d,Y'))
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Attendance</li>
    </ol>
@endsection
@section('page-content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><span class="text-muted"><b>ATTENDANCE MONITORING</b></span></h3>
            {{-- <div class="card-tools">
                
            </div> --}}
        </div>
        <div class="card-body p-0">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr class="text-center">
                        <th>EMPLOYEE</th>
                        <th>TIME IN</th>
                        <th>TIME OUT</th>
                        {{-- <th>HEALTH</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($_employees as $_data)
                        <tr>
                            <td style=" width:70px" class="">
                                <span class="text-info h5">
                                    <b> {{ strtoupper($_data->first_name . ' ' . $_data->last_name) }}</b><br>

                                </span>
                                <span class="text-muted ">
                                    <small> <b>{{ $_data->department }}</b></small>
                                </span>
                            </td>
                            <td class="text-center">


                                @if ($_data->staff_id)
                                    @if ($_data->daily_attendance)
                                        <span class="h4 text-info">
                                            {{ date_format(date_create($_data->daily_attendance->time_in), 'h:i:s a') }}
                                        </span>
                                    @else
                                        <span class="h4 text-info">
                                            -
                                        </span>
                                    @endif
                                @else
                                    <span class="h4 text-info">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($_data->daily_attendance)
                                    <span class="h4 text-info">
                                        @if ($_data->daily_attendance->time_out)
                                            {{ date_format(date_create($_data->daily_attendance->time_out), 'h:i:s a') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                @endif

                            </td>
                            {{-- <td>
                                @if ($_data->description)
                                    @php
                                        $_health = json_decode($_data->description);
                                        $_count = 0;
                                    @endphp
                                    @if ($_data->created_at->format('Y-m-d') == now()->format('Y-m-d'))
                                        @if ($_health->positive == 'NO')
                                            <span class="h4 text-success">
                                                <b>NORMAL</b>
                                            </span>
                                        @else
                                            @foreach ($_health->have_any as $item)
                                                <span class="text-orange">
                                                    <b>{{ $item }}</b>,
                                                </span>
                                                @php
                                                    if ($_count == 2) {
                                                        $_count = 0;
                                                        echo '<br>';
                                                    } else {
                                                        $_count += 1;
                                                    }
                                                @endphp
                                            @endforeach

                                            @foreach ($_health->experience as $item)
                                                <span class="text-orange">
                                                    <b>{{ $item }}</b>,
                                                </span>
                                                @php
                                                    if ($_count == 2) {
                                                        $_count = 0;
                                                        echo '<br>';
                                                    } else {
                                                        $_count += 1;
                                                    }
                                                @endphp
                                            @endforeach
                                        @endif
                                    @else
                                        <span class="h4 text-info">
                                            -
                                        </span>
                                    @endif
                                @else
                                    <span class="h4 text-info">
                                        -
                                    </span>
                                @endif
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
