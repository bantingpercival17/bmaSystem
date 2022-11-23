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
                                <b>{{ $_subject->academic->id > 4 ? '20%' : '15%' }}</b></th>
                            @for ($i = 1; $i <= 10; $i++)
                                <th style="width: 20px;">R{{ $i }}</th>
                            @endfor
                            <th style="width: 20px; text-align: center;">
                                <b>{{ $_subject->academic->id > 4 ? '20%' : '15%' }}</b></th>
                            <th style="width: 22px; text-align: center;">
                                {{ strtoupper(request()->input('_period'))[0] }}E
                            </th>
                            <th style="width: 22px; text-align: center;"><b>55%</b></th>
                            <th style="width: 30px; text-align: center;"><b>40%</b></th>
                            @for ($i = 1; $i <= 10; $i++)
                                <th style="width: 20px; text-align: center;">A{{ $i }}</th>
                            @endfor
                            <th>60%</th>
                            <th>(FG)</th>
                            <th>(GP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($_students->count() > 0)
                            @php
                                $count = 0;
                            @endphp
                            @foreach ($_students as $_key => $_student)
                                @php
                                    // Quiz
                                    $_quiz_avg = $_student->student->subject_average_score([$_subject->id, request()->input('_period'), 'Q']);
                                    $_quiz_percent = $_student->student->subject_score([$_subject->id, request()->input('_period'), 'Q1']) !== null ? ($_quiz_avg >= 0 ? number_format($_quiz_avg, 2) : null) : null;
                                    // Oral
                                    $_oral_avg = $_student->student->subject_average_score([$_subject->id, request()->input('_period'), 'O']);
                                    $_oral_percent = $_student->student->subject_score([$_subject->id, request()->input('_period'), 'O1']) !== null ? ($_oral_avg >= 0 ? number_format($_oral_avg, 2) : null) : null;
                                    // Output
                                    $_output_avg = $_student->student->subject_average_score([$_subject->id, request()->input('_period'), 'R']);
                                    $_output_percent = $_student->student->subject_score([$_subject->id, request()->input('_period'), 'R1']) !== null ? ($_output_avg >= 0 ? number_format($_output_avg, 2) : null) : null;
                                    // Examination
                                    $_exam_avg = $_student->student->subject_average_score([$_subject->id, request()->input('_period'), strtoupper(request()->input('_period'))[0] . 'E']);
                                    $_exam_percent = $_student->student->subject_score([$_subject->id, request()->input('_period'), strtoupper(request()->input('_period'))[0] . 'E1']) !== null ? number_format($_exam_avg, 2) : null;
                                    //$_exam_percent = $_exam_avg;
                                    // Lecture Grade
                                    $_lec_grade = $_student->student->lecture_grade([$_subject->id, request()->input('_period')]);
                                    $_lecture_grade = $_quiz_percent !== null && $_oral_percent !== null && $_output_percent !== null && $_exam_percent !== null ? number_format($_lec_grade, 2) : '';
                                    // Laboratory Grade
                                    $_lab_grade = $_student->student->laboratory_grade([$_subject->id, request()->input('_period')]);
                                    $_lab_grade = $_student->student->subject_score([$_subject->id, request()->input('_period'), 'A1']) !== null ? ($_lab_grade >= 0 ? number_format($_lab_grade, 2) : '') : '';
                                    
                                    $count += 1;
                                @endphp
                                <tr class="text-center">
                                    <td>{{ $_key + 1 }}</td>
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
                                        {{ $_quiz_percent }}
                                    </th>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <td>
                                            {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), 'O' . $i]) }}
                                        </td>
                                    @endfor
                                    <th>
                                        {{ $_oral_percent }}
                                    </th>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <td>
                                            {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), 'R' . $i]) }}
                                        </td>
                                    @endfor
                                    <th>
                                        {{ $_output_percent }}
                                    </th>
                                    <td>
                                        {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), strtoupper(request()->input('_period'))[0] . 'E1']) }}
                                    </td>
                                    <th>
                                        {{ $_exam_percent }}
                                    </th>
                                    <th>
                                        {{ $_lecture_grade }}
                                    </th>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <td>
                                            {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), 'A' . $i]) }}
                                        </td>
                                    @endfor
                                    <th>
                                        {{ $_lab_grade }}
                                    </th>
                                    <th>
                                        @php
                                            $_final = $_student->student->final_grade_v2($_subject->id, request()->input('_period'));
                                            $_final = $_lecture_grade !== '' ? number_format($_final, 2) : '';
                                        @endphp
                                        {{ $_final }}
                                    </th>
                                    <th>
                                        @php
                                            $_final_grade = $_final !== '' ? ($_final >= 0 && $_exam_avg >= 0 ? number_format($_student->student->percentage_grade(number_format($_final, 2)), 2) : 'INC') : '';
                                        @endphp
                                        {{ $_final_grade }}
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
