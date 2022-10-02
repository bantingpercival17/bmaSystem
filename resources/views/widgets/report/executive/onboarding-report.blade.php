{{-- @extends('widgets.report.report_layout') --}}
@extends('widgets.report.app_report_template')
@section('title-report', 'ONBOARDING MASTER LIST - ' . date('Ymd'))
@section('form-code', '')
@section('content')

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
                                        $first_day = new DateTime();
                                        $first_day->modify('Sunday');
                                    @endphp
                                    {{ strtoupper($first_day->format('F d, Y')) }}
                                </b>
                            </td>
                            <td>
                                END DATE:
                                <b>
                                    @php
                                        $first_day = new DateTime();
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

                        @if ($_section->student_section)
                            @foreach ($_section->student_section as $section)
                                <tr>
                                    <td style="padding-left: 10px; width:40%">
                                        {{ strtoupper($section->student->last_name . ', ' . $section->student->first_name . ' ' . $section->student->middle_name) }}
                                    </td>
                                    <td class="text-center"style="padding-left: 10px; width:45%">
                                        {{ $section->student->municipality . ', ' .$section->student->province }}
                                    </td>
                                    <td style="padding-left: 10px; width:30%">
                                        {{ $section->student->onboarding_attendance ? $section->student->onboarding_attendance->time_in : '' }}
                                    </td>
                                    <td style="padding-left: 10px; width:30%">
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
