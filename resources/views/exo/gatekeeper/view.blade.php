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
                        <th>HEALTH</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($_employees as $_data)
                        <tr>
                            <td style="text-align: center; width:70px" class="text-center"> @php
                                if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $_data->user->name)) . '.jpg'))) {
                                    $_image = strtolower(str_replace(' ', '_', $_data->user->name)) . '.jpg';
                                } else {
                                    $_image = 'avatar.png';
                                }
                            @endphp
                                <div class="image">
                                    <img src="{{ asset('/assets/img/staff/' . $_image) }}" class="img-circle elevation-2"
                                        alt="User Image" width="100px">
                                </div>
                                <span class="text-info h5">
                                    <b> {{ strtoupper($_data->first_name . ' ' . $_data->last_name) }}</b><br>

                                </span>
                                <span class="text-muted">
                                    <b>{{ $_data->department }}</b>
                                </span>
                            </td>
                            <td class="text-center">


                                @if ($_data->staff_id)
                                    @if ($_data->daily_attendance)
                                        <span class="h4 text-info">
                                            {{ date_format(date_create($_data->daily_attendance->time_in), 'h:i:s a'); /*->format('h:i:s a') */ }}
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
                                        {{ $_data->daily_attendance->time_out }}
                                        {{-- {{ date_format(date_create($_data->daily_attendance->time_out), 'h:i:s a') }} --}}
                                    </span>
                                @endif

                            </td>
                            <td>
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
