{{-- @extends('widgets.report.report_layout') --}}
{{-- @extends('widgets.report.layout_report') --}}
@extends('widgets.report.main-report-template')
@section('title-report', 'ONBOARDING MASTER LIST - ' . date('Ymd'))
@section('form-code', 'EX-O FORM NO.2')
@section('content')
    @php
        $now = request()->input('week') ? request()->input('week') : date('Y-m-d');
        $day = new DateTime($now);
        $week = date('l', strtotime($now));
        $modify = $week == 'Sunday' ? 'Sunday' : 'Last Sunday';
    @endphp

    @foreach ($_sections as $_section)
        {{-- <header>
            <label for="" class="form-code">BMA EXO FORM NO.2 </label>
            <div class="text-center">
                <img src="{{ public_path() . '/assets/image/report-header.png' }}" alt="page-header">
            </div>

        </header> --}}

        <div class="page-content">
            @if ($_section->count() > 0)
                <h3 class="text-center" style="margin:0px;"><b>ONBOARDING & LIBERTY RECORD</b></h3>
                <h4 class="text-center" style="margin-top:0px;">{{ $_section->year_level }} MIDSHIPMAN</h4>
                <table class="table table-header">
                    <tbody>
                        <tr>
                            <td>COURSE/SECTION: </td>
                            <td><b>{{ $_section->section_name }}</b></td>
                            <td>TOTAL STUDENTS: <b>{{ $_section->student_sections->count() }}</td>
                        </tr>
                        <tr>
                            <td>DATE RANGE:</td>
                            <td>
                                START DATE:
                                <b>
                                    @php
                                        $first_day = new DateTime($now);
                                        $first_day->modify($modify);
                                    @endphp
                                    {{ strtoupper($first_day->format('F d, Y')) }}
                                </b>
                            </td>
                            <td>
                                END DATE:
                                <b>
                                    @php
                                        $last_day = new DateTime($now);
                                        $last_day->modify('Next Saturday');
                                    @endphp
                                    {{ strtoupper($last_day->format('F d, Y')) }}
                                </b>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @php
                    $total_aboard = 0;
                    $contentNumber = 0;
                @endphp
                <table class="table table-onboard">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAME OF MIDSHIPMAN</th>
                            <th>ADDRESS</th>
                            <th>TIME IN</th>
                            <th>TIME OUT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($_section->student_sections) > 0)
                            @foreach ($_section->student_sections as $key => $section)
                                @php
                                    $contentNumber += 1;
                                    $_year_level = intval(str_replace('/C', '', $_section->year_level));
                                    $_style = '';
                                    if ($section->student->onboarding_attendance) {
                                        $date = new DateTime($section->student->onboarding_attendance->time_in);
                                        $_date = $date->format('Y-m-d');
                                        $time = $date->format('Hi');
                                        // echo $time;
                                        //$time = intval(str_replace(':', '', $section->student->onboarding_attendance->time_in));
                                        if ($_date != $first_day->format('Y-m-d')) {
                                            $_style = 'color:red; font-weight:bold;';
                                        }
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
                                <tr class="{{ $contentNumber >= 35 ? 'page-break' : '' }}">
                                    <td style=" width:5%">{{ $key + 1 }}</td>
                                    <td style=" width:40%">
                                        {{ strtoupper($section->student->last_name . ', ' . $section->student->first_name . ' ' . $section->student->middle_name) }}
                                    </td>
                                    <td> {{ $section->student->municipality . ', ' . $section->student->province }}</td>
                                    <td style=" {{ $_style }}" class="text-center">
                                        @php
                                            if ($section->student->onboarding_attendance) {
                                                $total_aboard += 1;
                                            }
                                        @endphp
                                        {{ $section->student->onboarding_attendance ? $section->student->onboarding_attendance->time_in : '' }}
                                    </td>
                                    <td class="text-center">

                                        {{ $section->student->onboarding_attendance ? $section->student->onboarding_attendance->time_out : '' }}
                                    </td>
                                </tr>
                                @if ($contentNumber >= 35)
                                @php
                                    $contentNumber = 0;
                                @endphp
                            @endif
                            @endforeach
                        @endif
                    </tbody>
                    {{-- <tbody>
                      
                        @if (count($_section->student_sections) > 0)
                            @foreach ($_section->student_sections as $key => $section)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td style=" width:40%">
                                        {{ strtoupper($section->student->last_name . ', ' . $section->student->first_name . ' ' . $section->student->middle_name) }}
                                    </td>
                                    <td class="text-center" style="width:45%">
                                        {{ $section->student->municipality . ', ' . $section->student->province }}
                                        @php
                                            $_year_level = intval(str_replace('/C', '', $_section->year_level));
                                            $_style = '';
                                            if ($section->student->onboarding_attendance) {
                                                $date = new DateTime($section->student->onboarding_attendance->time_in);
                                                $_date = $date->format('Y-m-d');
                                                $time = $date->format('Hi');
                                                // echo $time;
                                                //$time = intval(str_replace(':', '', $section->student->onboarding_attendance->time_in));
                                                if ($_date != $first_day->format('Y-m-d')) {
                                                    $_style = 'color:red; font-weight:bold;';
                                                }
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
                                    <td style=" width:30%; {{ $_style }}" class="text-center">
                                        @php
                                            if ($section->student->onboarding_attendance) {
                                                $total_aboard += 1;
                                            }
                                        @endphp
                                        {{ $section->student->onboarding_attendance ? $section->student->onboarding_attendance->time_in : '' }}
                                    </td>
                                    <td style="width:30%" class="text-center">

                                        {{ $section->student->onboarding_attendance ? $section->student->onboarding_attendance->time_out : '' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody> --}}
                </table>
                @if (Auth::user())
                    <div class="signatories">
                        <table class="table table-header ">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        TOTAL ABOARD : {{ $total_aboard }} CADET/S
                                        <small> (as of {{ date('F d, y H:m:s') }})</small>
                                        <br><br><br>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        PREPARED BY:
                                    </td>
                                    <td>
                                        VALIDATED BY:
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <u>
                                            <b>{{ strtoupper(Auth::user()->name) }}</b>
                                        </u>

                                    </td>
                                    <td>
                                        <u>
                                            <b>{{ strtoupper('2M Christian Dela Cruz') }}</b>
                                        </u>

                                    </td>
                                </tr>
                                <tr>
                                    <td><small>Tactical Officer</small> </td>
                                    <td><small>Executive Officer</small> </td>
                                </tr>
                                <tr>
                                    <td><small>DATE:</small>_____________________</td>
                                    <td><small>DATE:</small>_____________________</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="signatories">
                        <table class="table table-header">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        TOTAL ABOARD : {{ $total_aboard }} CADET/S
                                        <small> (as of {{ date('F d, y H:m:s') }})</small>
                                        <br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <u>
                                            <b>SYSTEM GENERATED AT {{ now() }}</b>
                                        </u>

                                    </td>
                                    <td>
                                        <u>
                                            <b>{{ strtoupper('severino bugarin') }}</b>
                                        </u>

                                    </td>
                                </tr>
                                <tr>
                                    <td><small></small> </td>
                                    <td><small>Executive Officer</small> </td>
                                </tr>
                                <tr>
                                    <td><small>DATE:</small>_____________________</td>
                                    <td><small>DATE:</small>_____________________</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </div>
        <div class="page-break"></div>
    @endforeach
@endsection
