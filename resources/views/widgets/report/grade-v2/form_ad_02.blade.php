@extends('widgets.report.main-report-template')
@section('title-report', 'FORM AD 02 - GRADING SHEET : ' . $_subject->curriculum_subject->subject->subject_code)
@section('form-code', 'AD - 02')
@section('content')
    <div class="page-content">
        <div class="summary-grade-header">
            <h2 class="text-center" style="margin:0px;">
                <b>REPORT OF GRADES</b>
            </h2>
        </div>
        <table class="table-content">
            <tbody>
                <tr>
                    <td style="width: 60%"><small>SUBJECT :</small>
                        <span><b>{{ $_subject->curriculum_subject->subject->subject_code }}</b></span>
                    </td>
                    <td><small>SCHOOL YEAR:</small>
                        <span><b>{{ $_subject->academic->school_year . ' | ' . $_subject->academic->semester }}</b></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <small>COURSE:</small>
                        <span><b>{{ $_subject->section->section_name }}</b></span>
                    </td>
                    <td>
                        <small>TEACHER:</small>
                        <span><b>{{ $_subject->staff->first_name . ' ' . $_subject->staff->last_name }}</b></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="table-outline-2 table-student-content">
            @php
                $contentNumber = 0;
                $contentCount = 40;
            @endphp
            <thead>
                <tr>
                    <th></th>
                    <th colspan="2">NAME OF MIDSHIPMAN</th>
                    <th colspan="2">MIDTERM</th>
                    <th colspan="2">FINAL</th>
                    <th rowspan="2">REMARKS</th>
                </tr>
                <tr class="text-center">
                    <td style="width: 20px; text-align: center;">NO</td>
                    <td style="width: 60px; text-align: center;">STD NO.</td>
                    <td style="width: 200px;text-align: center;">COMPLETE NAME</td>
                    <td>PERCENT</td>
                    <td>RANGE</td>
                    <td>PERCENT</td>
                    <td>RANGE</td>
                </tr>
            </thead>
            <tbody>
                @if ($_students->count() > 0)
                    @foreach ($_students as $_key => $_student)
                        @php
                            $contentNumber += 1;
                        @endphp
                        <tr class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                            <td class="text-center">{{ $_key + 1 }}</td>
                            <td class="text-center">
                                {{ $_student->student->account ? $_student->student->account->student_number : '' }}
                            </td>
                            <td style="padding-left: 10px;">
                                {{ strtoupper($_student->student->last_name . ', ' . $_student->student->first_name) }}
                            </td>
                            <td class="text-center">
                                {{ $_student->student->period_final_grade('midterm') }}
                            </td>
                            <td class="text-center">
                                <b>
                                    {{ $_student->student->point_grade('midterm') }}</b>
                            </td>
                            <td class="text-center">
                                @if ($_subject->academic_id >= 5)
                                    {{ $_student->student->total_final_grade() }}
                                @else
                                    {{ $_student->student->period_final_grade('finals') }}</b>
                                @endif

                            </td>
                            <td class="text-center">
                                <b>
                                    @if ($_subject->academic_id >= 5)
                                        {{ $_student->student->total_final_grade() !== '' ? $_student->student->point_grade('finals') : 'INC' }}
                                    @else
                                        {{ $_student->student->point_grade('finals') }}
                                    @endif
                                </b>
                            </td>
                            <td class="text-center fw-bolder">
                                <b>
                                    @if ($_subject->academic_id >= 5)
                                        {{ $_student->student->total_final_grade() !== '' ? ($_student->student->point_grade('finals') >= 5 ? 'FAILED' : 'PASSED') : '' }}
                                    @else
                                        {{ $_student->student->point_grade('finals') >= 5 ? 'FAILED' : 'PASSED' }}
                                    @endif

                                </b>
                            </td>
                        </tr>
                        @if ($contentNumber >= $contentCount)
                            @php
                                $contentNumber = 0;
                            @endphp
                        @endif
                    @endforeach
                @endif

            </tbody>
        </table>
        <div class="signatories">
            <br>
            <table class="table table-header ">
                <tbody>
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
                                <b>{{ strtoupper($_subject->staff->first_name . ' ' . $_subject->staff->last_name) }}</b>
                            </u>
                        </td>
                        <td>
                            <u>
                                @if ($_subject->finals_grade_submission)
                                    <b>{{ strtoupper($_subject->finals_grade_submission->approved_by) }}</b>
                                @endif

                            </u>
                        </td>
                    </tr>
                    <tr>
                        <td><small>Subject Teacher</small> </td>
                        <td><small>Department Head</small> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
