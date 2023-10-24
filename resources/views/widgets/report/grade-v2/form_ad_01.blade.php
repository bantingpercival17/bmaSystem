@extends('widgets.report.grade-v2.report_layout_1')
@section('title-report', 'FORM AD 01 - GRADING SHEET : ' . $_subject->curriculum_subject->subject->subject_code)
@section('form-code', 'AD - 01')
@section('content')
    <div class="content">
        <div class="row">
            <table class="table-content">
                <tbody>
                    <tr>
                        <td><small>SUBJECT :</small>
                            <span><b>{{ $_subject->curriculum_subject->subject->subject_code }}</b></span>
                        </td>
                        <td style="width: 50%"></td>
                        <td><small>SCHOOL YEAR:</small>
                            <span><b>{{ strtoupper($_subject->academic->school_year) }}</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small>COURSE:</small>
                            <span><b>{{ $_subject->curriculum_subject->course->course_code }}</b></span>
                        </td>
                        <td style="width: 50%" class="text-center">
                            <b> OFFICIAL CLASS RECORD</b>
                        </td>
                        <td>
                            <small>SEMESTER:</small>
                            <span><b>{{ strtoupper($_subject->academic->semester) }}</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small>YEAR & SECTION: </small>
                            <span><b>{{ $_subject->section->section_name }}</b></span>
                        </td>
                        <td style="width: 50%" class="text-center">

                        </td>
                        <td>
                            <small>PERIOD</small>
                            <span><b>{{ strtoupper(request()->input('_period')) }}</b></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div>
                <table class="table-2">
                    <thead>
                        <tr class="text-center">
                            <th colspan="3" class="fixed">NAME OF MIDSHIPMAN</th>
                            <th colspan="11">QUIZZES</th>
                            <th colspan="6">ORAL</th>
                            <th colspan="11">R W - OUTPUT</th>
                            <th colspan="2">
                                {{ strtoupper(request()->input('_period')) }} EXAMS</th>
                            <th>LECT GRADE</th>
                            <th colspan="10">Scientific and Technical Experiments Demonstrations of Competencies Acquired
                            </th>
                            <th>LAB GRADE</th>
                            <th>{{ strtoupper(request()->input('_period')) }} GRADE</th>
                            @if (request()->input('_period') == 'finals')
                                @if ($_subject->academic->id > 4)
                                    <th>CO GRADE</th>
                                    <th>TOTAL FINAL GRADE</th>
                                @endif
                            @endif
                            <th>POINT GRADE</th>
                        </tr>
                        <tr class="text-center">
                            <td style="width: 20px; text-align: center;">NO</td>
                            <td style="width: 40px; text-align: center;">STD NO.</td>
                            <td style="width: 170px;text-align: center;">COMPLETE NAME</td>
                            @for ($i = 1; $i <= 10; $i++)
                                <td style="width: 20px;"><b>Q{{ $i }}</b></td>
                            @endfor
                            <th style="width: 20px; text-align: center;"><b>15%</b></th>
                            @for ($i = 1; $i <= 5; $i++)
                                <td style="width: 20px;"><b>O{{ $i }}</b></td>
                            @endfor
                            <th style="width: 20px; text-align: center;">
                                <b>{{ $_subject->academic->id > 4 ? '20%' : '15%' }}</b>
                            </th>
                            @for ($i = 1; $i <= 10; $i++)
                                <th style="width: 20px;">R{{ $i }}</th>
                            @endfor
                            <th style="width: 20px; text-align: center;">
                                <b>{{ $_subject->academic->id > 4 ? '20%' : '15%' }}</b>
                            </th>
                            <th style="width: 22px; text-align: center;">
                                {{ strtoupper(request()->input('_period'))[0] }}E
                            </th>
                            <th style="width: 22px; text-align: center;">
                                <b>{{ $_subject->academic->id > 4 ? '45%' : '55%' }}</b>
                            </th>
                            <th style="width: 30px; text-align: center;">
                                <b>{{ $_subject->academic->id > 4 ? '50%' : '40%' }}</b>
                            </th>
                            @for ($i = 1; $i <= 10; $i++)
                                <th style="width: 20px; text-align: center;">A{{ $i }}</th>
                            @endfor
                            <th>{{ $_subject->academic->id > 4 ? '50%' : '60%' }}</th>
                            <th>(FG)</th>
                            @if (request()->input('_period') == 'finals')
                                @if ($_subject->academic->id > 4)
                                    <th></th>
                                    <th></th>
                                @endif
                            @endif
                            <th>(GP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($_students->count() > 0)
                            @php
                                $count = 0;
                            @endphp
                            @foreach ($_students as $_key => $_student)
                                <tr class="text-center">
                                    <td>
                                        {{ $_key + 1 }}
                                    </td>
                                    <td>{{ $_student->student->account ? $_student->student->account->student_number : '' }}
                                    </td>
                                    <td>{{ strtoupper($_student->student->last_name . ', ' . $_student->student->first_name) }}
                                    </td>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <td>
                                            {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), 'Q' . $i]) }}
                                        </td>
                                    @endfor
                                    <th>
                                        {{-- Quiz Average --}}
                                        {{ $_student->student->quizzes_average(request()->input('_period')) }}
                                    </th>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <td>
                                            {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), 'O' . $i]) }}
                                        </td>
                                    @endfor
                                    <th>
                                        {{-- Oral Average --}}
                                        {{ $_student->student->oral_average(request()->input('_period')) }}
                                    </th>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <td>
                                            {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), 'R' . $i]) }}
                                        </td>
                                    @endfor
                                    <th>
                                        {{-- Research Word Output Average --}}
                                        {{ $_student->student->research_work_average(request()->input('_period')) }}
                                    </th>
                                    <td>
                                        {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), strtoupper(request()->input('_period'))[0] . 'E1']) }}
                                    </td>
                                    <th>
                                        {{ $_student->student->examination_average(request()->input('_period')) }}
                                    </th>
                                    <th>
                                        {{ $_student->student->lecture_grade_v2(request()->input('_period')) }}
                                    </th>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <td>
                                            {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), 'A' . $i]) }}
                                        </td>
                                    @endfor
                                    <th>
                                        {{ $_student->student->laboratory_grade_v2(request()->input('_period')) }}
                                    </th>
                                    <th>
                                        {{ $_student->student->period_final_grade(request()->input('_period')) }}
                                    </th>
                                    @if (request()->input('_period') == 'finals')
                                        @if ($_subject->academic->id >= 5)
                                            <th>{{ $_student->student->course_outcome_avarage() }}</th>
                                            <th>{{ $_student->student->total_final_grade() }}</th>
                                        @endif
                                    @endif
                                    <th>
                                        @if ($_student->student->enrollment_assessment_paid->enrollment_cancellation)
                                            DROPPED
                                        @else
                                            {{ $_student->student->point_grade(request()->input('_period')) }}
                                        @endif

                                    </th>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="46">No Students</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

    @endsection
