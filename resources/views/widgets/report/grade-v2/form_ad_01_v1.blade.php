{{-- @extends('widgets.report.grade-v2.report_layout_1') --}}
{{-- @extends('widgets.report.main-report-template') --}}
@extends('widgets.report.app_report_template_v2')
@section('title-report', 'FORM AD 01 - GRADING SHEET : ' . $subject->curriculum_subject->subject->subject_code)
@section('form-code', 'AD - 01')
@section('content')
    <div class="page-content" style="margin-top: 30px;">
        <table class="table-content">
            <tbody>
                <tr>
                    <td><small>SUBJECT: </small>
                        <span><b>{{ $subject->curriculum_subject->subject->subject_code }}</b></span>
                    </td>
                    <td style="width: 50%"></td>
                    <td><small>SCHOOL YEAR:</small>
                        <span><b>{{ strtoupper($subject->academic->school_year) }}</b></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <small>COURSE: </small>
                        <span><b>{{ $subject->curriculum_subject->course->course_name }}</b></span>
                    </td>
                    <td style="width: 50%" class="text-center">
                        <b> OFFICIAL CLASS RECORD</b>
                    </td>
                    <td>
                        <small>SEMESTER: </small>
                        <span><b>{{ strtoupper($subject->academic->semester) }}</b></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <small>YEAR & SECTION: </small>
                        <span><b>{{ $subject->section->section_name }}</b></span>
                    </td>
                    <td style="width: 50%" class="text-center">

                    </td>
                    <td>
                        <small>PERIOD: </small>
                        <span><b>{{ strtoupper($period) }}</b></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table-subject-grade">
            <thead>
                @foreach ($columns as $key => $item)
                    <tr>
                        @foreach ($item as $count => $header)
                            @if ($key > 0)
                                @if ($header[1] > 1)
                                    @for ($i = 0; $i < $header[1]; $i++)
                                        <th class="text-center">
                                            {{ strtoupper($header[0]) . ($i + 1) }}
                                        </th>
                                    @endfor
                                @else
                                    <th style="width: {{ $header[2] }}">
                                        {{ strtoupper($header[0]) }}
                                    </th>
                                @endif
                            @else
                                <th colspan="{{ $header[1] }}">
                                    {{ strtoupper($header[0]) }}
                                </th>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </thead>
            <tbody>
                @foreach ($students as $key => $student)
                    @php
                        $contentNumber += 1;
                    @endphp
                    <tr class="{{ $contentNumber >= $contentCount ? 'page-break' : '' }}">
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">
                            {{ $student->student->account ? $student->student->account->student_number : '' }}
                        </td>
                        <td style="padding-left: 10px;">
                            {{ strtoupper($student->student->last_name . ', ' . $student->student->first_name) }}
                        </td>
                        @if ($student->student->enrollment_academic_year($subject->academic->id)->enrollment_cancellation)
                            <td colspan="{{ $totalColSpan }}" class="text-danger fw-bolder text-center">STUDENT
                                DROPPED</td>
                        @else
                            @foreach ($columns[1] as $headerCount => $header)
                                @if ($headerCount > 2)
                                    @if ($header[1] > 1)
                                        @for ($i = 0; $i < $header[1]; $i++)
                                            <td class="text-center">
                                                {{ $student->student->subject_score([$subject->id, $period, strtoupper($header[0]) . ($i + 1)]) }}
                                            </td>
                                        @endfor
                                    @else
                                        <td class="text-center">
                                            <b>
                                                @if ($headerCount == 4)
                                                    <!--Quiz Average-->
                                                    {{ $student->student->quizzes_average($period, $subject) }}
                                                @elseif($headerCount == 6)
                                                    <!-- Oral Average -->
                                                    {{ $student->student->oral_average($period, $subject) }}
                                                @elseif($headerCount == 8)
                                                    {{ $student->student->research_work_average($period, $subject) }}
                                                @elseif($headerCount == 9)
                                                    {{ $student->student->subject_score([$subject->id, $period, strtoupper($period)[0] . 'E1']) }}
                                                @elseif($headerCount == 10)
                                                    {{ $student->student->examination_average($period, $subject) }}
                                                @else
                                                    @if (!$subject->curriculum_subject->subject->laboratory_hours > 0)
                                                        @if ($headerCount == 11)
                                                            {{ $student->student->period_final_grade($period, $subject) }}
                                                        @elseif($headerCount == 12)
                                                            @if ($period == 'midterm')
                                                                {{ $student->student->point_grade($period, $subject) }}
                                                            @else
                                                                @if ($subject->academic->id >= 5)
                                                                    {{ $student->student->course_outcome_avarage($subject) }}
                                                                @endif
                                                            @endif
                                                        @elseif($headerCount == 13)
                                                            @if ($subject->academic->id >= 5)
                                                                {{ $student->student->total_final_grade($subject) }}
                                                            @endif
                                                        @elseif($headerCount == 14)
                                                            {{ $student->student->point_grade($period, $subject) }}
                                                        @else
                                                            {{ $headerCount }}
                                                        @endif
                                                    @else
                                                        @if ($headerCount == 11)
                                                            {{ $student->student->lecture_grade_v2($period, $subject) }}
                                                        @elseif($headerCount == 13)
                                                            {{ $student->student->laboratory_grade_v2($period, $subject) }}
                                                        @elseif($headerCount == 14)
                                                            {{ $student->student->period_final_grade($period, $subject) }}
                                                        @elseif($headerCount == 15)
                                                            @if ($period == 'midterm')
                                                                {{ $student->student->point_grade($period, $subject) }}
                                                            @else
                                                                @if ($subject->academic->id >= 5)
                                                                    {{ $student->student->course_outcome_avarage($subject) }}
                                                                @endif
                                                            @endif
                                                        @elseif($headerCount == 16)
                                                            @if ($subject->academic->id >= 5)
                                                                {{ $student->student->total_final_grade($subject) }}
                                                            @endif
                                                        @elseif($headerCount == 17)
                                                            {{ $student->student->point_grade($period, $subject) }}
                                                        @else
                                                            {{ $headerCount }}
                                                        @endif
                                                    @endif
                                                @endif


                                            </b>
                                        </td>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </tr>
                    @if ($contentNumber >= $contentCount)
                        @php
                            $contentNumber = 0;
                        @endphp
                    @endif
                @endforeach
            </tbody>
        </table>
        {{-- Signatories --}}
        @include('widgets.report.grade-v2.form_ad_signatories')
    </div>
@endsection
