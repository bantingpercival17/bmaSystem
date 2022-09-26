{{-- @extends('widgets.report.report_layout') --}}
@extends('widgets.report.grade-v2.report_layout_1')
@section('title-report', 'LIBERTY REPORT - ' . date('Ymd'))
@section('form-code', '')
@section('content')
    <div class="content">
        @foreach ($_sections as $_section)
            @if ($_section->count() > 0)
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
                                START DAY:
                                <b>
                                    @php
                                        $first_day = new DateTime();
                                        $first_day->modify('Last Sunday');
                                    @endphp
                                    {{ $first_day->format('F d, Y') }}
                                </b>
                            </td>
                            <td>
                                END DAY:
                                <b>
                                    @php
                                        $first_day = new DateTime();
                                        $first_day->modify('Next Saturday');
                                    @endphp
                                    {{ $first_day->format('F d, Y') }}
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
                            @foreach ($_section->student_section as $_student)
                                <tr>
                                    <td style="padding-left: 10px; width:30%">
                                        {{ strtoupper($_student->student->last_name . ', ' . $_student->student->first_name . ' ' . $_student->student->middle_name) }}
                                    </td>
                                    <td class="text-center"style="padding-left: 10px; width:45%">

                                    </td>
                                    <td></td>
                                    <td>
                                        {{ $_student->onboarding_attendance }}
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
            @endif
           
        @endforeach
    </div>
@endsection
