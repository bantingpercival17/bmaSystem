{{-- @extends('widgets.report.report_layout') --}}
@extends('widgets.report.layout_report')
@section('title-report', 'Examination Permit')
@section('style')
    <style>
        .page-border {
            padding: 10px;
            margin: 10px;
            border: 5px solid #ddd;
        }

        .permit-border {
            width: 29%;
            display: inline-block;
            border-style: solid;
            border-width: medium;
            padding: 10px;
            margin-top: 5px;
            /* display: inline-block; */
        }

        .header-permit,
        .permit-footer {
            text-align: center
        }

        .text-small {
            font-size: 10px;
        }

        .text-medium {
            font-size: 12px;
        }

        .text-underline {
            text-decoration: underline;
            width: 100%
        }

        .table-permit-header {
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
            width: 100%;

            /*  border: 1px solid #ddd;
                border: 1px solid #ddd; */
        }

        .table-permit-header td,
        .table-permit-header th {
            padding-top: 0px;
            padding-bottom: 0px;
            /* border: 1px solid #ddd; */
            font-size: 10px;

        }

        .row-height {
            height: 7px;
            ;
        }
    </style>
@endsection
@section('content')
    <div class="page-border">
        @for ($q = 0; $q < 12; $q++)
        <div class="permit-border">
            <small class="text-small">BMA-FORM ACC-021</small> <br>
            <div class="header-permit">
                <label for="" class="permit-header"><b>BALIWAG MARITIME ACADEMY, INC.</b></label> <br>
                <small class="permit-subheader text-small">NO {{$q}}</small> <br>
                <small class="permit-subheader text-small">Academic Year</small> <br>
            </div>
            <table class="table-permit-header">
                <thead>
                    <tr>
                        <td>NAME:</td>
                        <th>0</th>
                    </tr>
                    <tr>
                        <td>STUDENT NO:</td>
                        <th>0</th>
                    </tr>
                    <tr>
                        <td>COURSE:</td>
                        <th>0</th>
                    </tr>
                </thead>
            </table>
            <h4 style="text-align:center; padding:0px; margin: 0px;">{{ strtoupper(request()->input('term')) }} EXAM</h4>
            <table class="table-2">
                <thead>
                    <tr>
                        <th>SUBJECT</th>
                        <th>INSTRUCTOR'S <br> SIGNATURE</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i <= 10; $i++)
                        <tr>
                            <th class="row-height"></th>
                            <th></th>
                        </tr>
                    @endfor
                </tbody>
            </table>
            <div class="permit-footer">
                <span class="text-small">Valid only when duly signed.</span> <br> <br>

                <label class="text-medium text-underline"><b>IRENE CAMACHO</b></label>
                <br>
                <label class="text-medium">Authorized Signature</label>
            </div>

        </div>
    @endfor

      
    </div>

@endsection
@section('content-content')
    <div class="page-border">
        @foreach ($sections as $section)
            <div class="permit-border">
                <small class="text-small">BMA-FORM ACC-021</small> <br>
                <div class="header-permit">
                    <label for="" class="permit-header"><b>BALIWAG MARITIME ACADEMY, INC.</b></label> <br>
                    <small class="permit-subheader text-small">NO</small> <br>
                    <small class="permit-subheader text-small">Academic Year</small> <br>
                </div>
                <table class="table-permit-header">
                    <thead>
                        <tr>
                            <td>NAME:</td>
                            <th>0</th>
                        </tr>
                        <tr>
                            <td>STUDENT NO:</td>
                            <th>0</th>
                        </tr>
                        <tr>
                            <td>COURSE:</td>
                            <th>0</th>
                        </tr>
                    </thead>
                </table>
                <h4 style="text-align:center; padding:0px; margin: 0px;">{{ strtoupper(request()->input('term')) }} EXAM
                </h4>
                <table class="table-2">
                    <thead>
                        <tr>
                            <th>SUBJECT</th>
                            <th>INSTRUCTOR'S <br> SIGNATURE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i <= 10; $i++)
                            <tr>
                                <th class="row-height"></th>
                                <th></th>
                            </tr>
                        @endfor
                    </tbody>
                </table>
                <div class="permit-footer">
                    <span class="text-small">Valid only when duly signed.</span> <br> <br>

                    <label class="text-medium text-underline"><b>IRENE CAMACHO</b></label>
                    <br>
                    <label class="text-medium">Authorized Signature</label>
                </div>

            </div>

            <div class="page-content">
                <div class="content">

                    {{--  @if ($_section->count() > 0)
                <h3 class="text-center" style="margin:0px;"><b>ONBOARDING & LIBERTY RECORD</b></h3>
                <h4 class="text-center" style="margin-top:0px;">{{ $_section->year_level }} MIDSHIPMAN</h4>
                <table class="table-content">
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
                <table class="table-2 ">
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
                        @php
                            $total_aboard = 0;
                        @endphp
                        @if (count($_section->student_sections) > 0)
                            @foreach ($_section->student_sections as $key => $section)
                                <tr>
                                    <th>{{ $key + 1 }}</th>
                                    <td style="padding-left: 10px; width:40%">
                                        {{ strtoupper($section->student->last_name . ', ' . $section->student->first_name . ' ' . $section->student->middle_name) }}
                                    </td>
                                    <td class="text-center" style="padding-left: 10px; width:45%">
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
                                    <td style="padding-left: 10px; width:30%; {{ $_style }}" class="text-center">
                                        @php
                                            if ($section->student->onboarding_attendance) {
                                                $total_aboard += 1;
                                            }
                                        @endphp
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
                @if (Auth::user())
                    <div class="signatories">
                        <table class="table-content">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        TOTAL ABOARD : {{ $total_aboard }} CADET/S
                                        <small> (as of {{ date('F d, y H:m:s') }})</small>
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
                                            <b>{{ strtoupper('severino bugarin') }}</b>
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
                        <table class="table-content">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        TOTAL ABOARD : {{ $total_aboard }} CADET/S
                                        <small> (as of {{ date('F d, y H:m:s') }})</small>
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
            @endif --}}
                </div>
            </div>
            <div class="page-break"></div>
        @endforeach
    </div>

@endsection
