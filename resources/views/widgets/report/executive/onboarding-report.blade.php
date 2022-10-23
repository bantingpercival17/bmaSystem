{{-- @extends('widgets.report.report_layout') --}}
@extends('widgets.report.app_report_template')
@section('title-report', 'ONBOARDING MASTER LIST - ' . date('Ymd'))
@section('form-code', '')
@section('content')
    @php
        $now = request()->input('week');
        $day = new DateTime($now);
        $week = date('l', strtotime($now));
        $modify = $week == 'Sunday' ? 'Sunday' : 'Last Sunday';
    @endphp
    @foreach ($_sections as $_section)
        @if ($_section->count() > 0)
            <div class="content">
                <h3 class="text-center"><b>ONBOARDING MASTER LIST</b></h3>
                <table class="table-content">
                    <tbody>
                        <tr>
                            <td>SECTION: </td>
                            <td><b>{{ $_section->section_name }}</b></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>DATE RANGE:</td>
                            <td>
                                START DATE:
                                <b>
                                    @php
                                        $first_day = new DateTime(request()->input('week'));
                                        $first_day->modify($modify);
                                    @endphp
                                    {{ strtoupper($first_day->format('F d, Y')) }}
                                </b>
                            </td>
                            <td>
                                END DATE:
                                <b>
                                    @php
                                        $first_day = new DateTime(request()->input('week'));
                                        $first_day->modify('Next Saturday');
                                    @endphp
                                    {{ strtoupper($first_day->format('F d, Y')) }}
                                </b>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table-2 ">
                    <thead>
                        <tr>
                            <th>NAME OF MIDSHIPMAN</th>
                            <th>ADDRESS</th>
                            <th>TIME IN</th>
                            <th>TIME OUT</th>
                        </tr>
                        <tr>

                        </tr>
                    </thead>
                    <tbody>

                        @if (count($_section->student_sections) > 0)
                            @foreach ($_section->student_sections as $section)
                                <tr>
                                    <td style="padding-left: 10px; width:40%">
                                        {{ strtoupper($section->student->last_name . ', ' . $section->student->first_name . ' ' . $section->student->middle_name) }}
                                    </td>
                                    <td class="text-center"style="padding-left: 10px; width:45%">
                                        {{ $section->student->municipality . ', ' . $section->student->province }}
                                        @php
                                            $_year_level = intval(str_replace('/C', '', $_section->year_level));
                                            $_style = '';
                                            if ($section->student->onboarding_attendance) {
                                                $date = new DateTime($section->student->onboarding_attendance->time_in);
                                                $time = $date->format('Hi');
                                                //  echo $time;
                                                //$time = intval(str_replace(':', '', $section->student->onboarding_attendance->time_in));
                                                foreach ($_time_arrival as $item) {
                                                    if ($item['year_level'] == $_year_level) {
                                                        //echo '<br>' . $time . ' | ' . $item['time_arrival'];
                                                        if ($time > $item['time_arrival']) {
                                                            $_style = 'color:red; font-weight:bold;';
                                                        }
                                                    }
                                                }
                                            }
                                            
                                        @endphp
                                    </td>
                                    <td style="padding-left: 10px; width:30%; {{ $_style }}" class="text-center">
                                        {{ $section->student->onboarding_attendance ? $section->student->onboarding_attendance->time_in : '' }}
                                    </td>
                                    <td style="padding-left: 10px; width:30%" class="text-center">
                                        {{ $section->student->onboarding_attendance ? $section->student->onboarding_attendance->time_out : '' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <td>TOTAL NUMBER OF CADETS : <b>{{ $_section->student_section->count() }}</b></td>
                        </tr>
                    </thead>
                </table>
                <div class="page-break"></div>

            </div>
        @endif

    @endforeach
@endsection
