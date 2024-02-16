@extends('widgets.report.main-report-template')
@section('title-report', 'FORM AD 02 - GRADING SHEET : ' . $subject->curriculum_subject->subject->subject_code)
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
                        <span><b>{{ $subject->curriculum_subject->subject->subject_code }}</b></span>
                    </td>
                    <td><small>SCHOOL YEAR:</small>
                        <span><b>{{ $subject->academic->school_year . ' | ' . $subject->academic->semester }}</b></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <small>COURSE:</small>
                        <span><b>{{ $subject->section->section_name }}</b></span>
                    </td>
                    <td>
                        <small>TEACHER:</small>
                        <span><b>{{ $subject->staff->first_name . ' ' . $subject->staff->last_name }}</b></span>
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
                @if ($students->count() > 0)
                    @foreach ($students as $_key => $_student)
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

                            @if ($_student->student->enrollment_academic_year($subject->academic->id)->enrollment_cancellation)
                                <td colspan="5" class="text-danger fw-bolder text-center">STUDENT
                                    DROPPED</td>
                            @else
                                @if (
                                    $subject->curriculum_subject->subject->subject_code == 'NSTP 1' ||
                                        $subject->curriculum_subject->subject->subject_code == 'NSTP 2')
                                    <td class="text-center">

                                    </td>
                                    <td class="text-center">

                                    </td>
                                    <td class="text-center">
                                        @php
                                            $_percentage = 0;
                                            $grade = $subject->student_computed_grade($_student->student_id)->first();

                                        @endphp
                                        {{ base64_decode($grade->final_grade) }}
                                    </td>
                                    <td class="text-center">
                                        <b>
                                            {{ $_student->student->percentage_grade(base64_decode($grade->final_grade)) }}
                                        </b>
                                    </td>
                                    <td class="text-center fw-bolder">
                                        <b>
                                            @if ($subject->academic_id >= 5)
                                                {{ $_student->student->total_final_grade($subject) !== '' ? ($_student->student->point_grade('finals', $subject) >= 5 ? 'FAILED' : 'PASSED') : '' }}
                                            @else
                                                {{ $_student->student->point_grade('finals', $subject) >= 5 ? 'FAILED' : 'PASSED' }}
                                            @endif

                                        </b>
                                    </td>
                                @else
                                    <td class="text-center">
                                        {{ $_student->student->period_final_grade('midterm', $subject) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($_student->student->point_grade('midterm', $subject) !== '')
                                            <b>
                                                {{ $_student->student->point_grade('midterm', $subject) }}</b>
                                        @endif
                                    </td>
                                    <td class="text-center">

                                        @if ($subject->academic_id >= 5)
                                            {{ $_student->student->total_final_grade($subject) }}
                                        @else
                                            {{ $_student->student->period_final_grade('finals', $subject) }}</b>
                                        @endif

                                    </td>
                                    <td class="text-center">
                                        <b>
                                            @if ($subject->academic_id >= 5)
                                                {{ $_student->student->total_final_grade($subject) !== '' ? $_student->student->point_grade('finals', $subject) : '' }}
                                            @else
                                                {{ $_student->student->point_grade('finals', $subject) }}
                                            @endif
                                        </b>
                                    </td>
                                    <td class="text-center fw-bolder">
                                        <b>
                                            @if ($subject->academic_id >= 5)
                                                {{ $_student->student->total_final_grade($subject) !== '' ? ($_student->student->point_grade('finals', $subject) >= 5 ? 'FAILED' : 'PASSED') : '' }}
                                            @else
                                                {{ $_student->student->point_grade('finals', $subject) >= 5 ? 'FAILED' : 'PASSED' }}
                                            @endif

                                        </b>
                                    </td>
                                @endif
                            @endif

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
        {{-- Signatories --}}
        @include('widgets.report.grade-v2.form_ad_signatories')
    </div>

@endsection
