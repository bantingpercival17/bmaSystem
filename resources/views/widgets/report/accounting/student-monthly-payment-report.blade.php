{{-- @extends('widgets.report.grade.report_layout_1') --}}
@extends('widgets.report.app_report_template')
@section('title-report', '')
@section('form-code', '')
@section('style')
    <style>
        .account-card-title {
            text-align: center;
            font-weight: 700;
            width: 100%;
        }

        .account-table {
            font-family: Arial, Helvetica, sans-serif;
        }

        .account-table td,
        .account-table th {
            padding: 0px 0px 0px 0px;
            font-size: 10px;


        }

        .account-table th,
            {
            text-align: center;
        }

        .checkbox-container {
            padding-top: 15px;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }

        .checkbox-input {
            margin: 0 14px 0 14px;
            vertical-align: middle;
            position: relative;
            top: 3px;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        @foreach ($_sections as $_section)
            @if ($_section->count() > 0)
                <h3 class="text-center"><b>MONTHLY PAYMENT MONITORING</b></h3>
                <table class="table-content">
                    <tbody>
                        <tr>
                            <td style="width: 60%"><small>SECTION :</small>
                                <span><b>{{ $_section->section_name }}</b></span>
                            </td>
                            <td><small>SCHOOL YEAR:</small>
                                <span><b>{{ $_section->academic->semester . ' ' . $_section->academic->school_year }}</b></span>
                            </td>
                        </tr>
                        {{--  <tr>
                            <td>
                                <small>COURSE:</small>
                                <span><b></b></span>
                            </td>
                            <td>
                                <small>TEACHER:</small>
                                <span><b></b></span>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
                @php
                    $_particular = ['UPON ENROLLMENT', '1ST MONTHLY', '2ND MONTHLY', '3RD MONTHLY', '4TH MONTHLY'];
                @endphp
                <table class="table-content table-2">
                    <thead>
                        <tr>
                            <th>STUDENT NUMBER</th>
                            <th>COMPLETE NAME</th>
                            @foreach ($_particular as $item)
                                <th>{{ $item }}</th>
                            @endforeach
                            <th>REMAINING BALANCE</th>
                            <th>TOTAL BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($_section->student_section)
                            @foreach ($_section->student_section as $_student)
                                <tr>
                                    <td class="text-center">
                                        {{ $_student->student->account->student_number }}
                                    </td>
                                    <td>{{ strtoupper($_student->student->last_name . ', ' . $_student->student->first_name . ' ' . $_student->student->middle_name) }}
                                    </td>
                                    @if ($_student->student->enrollment_assessment->payment_assessments->payment_mode == 1)
                                        @foreach ($_particular as $item)
                                            <td class="text-center">
                                                @if ($student = $_student->student)
                                                    @if ($student->enrollment_assessment)
                                                        @if ($student->enrollment_assessment->payment_assessments)
                                                        @endif
                                                    @endif
                                                @endif
                                                {{ $_student->student->enrollment_assessment->payment_assessments->payment_remarks($item) ?: '' }}
                                            </td>
                                        @endforeach
                                    @else
                                        <td colspan="{{ count($_particular) }}" class="text-center">
                                            {{ $_student->student->enrollment_assessment->payment_assessments->payment_remarks($_particular[0]) ?: '' }}
                                        </td>
                                    @endif

                                    <td></td>
                                    <td></td>
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
