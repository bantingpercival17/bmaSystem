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
                                @php
                                    $_terms = request()->input('_period');
                                    // Quiz
                                    $_quiz_avg = $_student->student->subject_average_score([$_subject->id, $_terms, 'Q']);
                                    $_quiz_percent = $_student->student->subject_score([$_subject->id, $_terms, 'Q1']) !== null ? ($_quiz_avg >= 0 ? number_format($_quiz_avg, 2) : null) : null;
                                    // Oral
                                    $_oral_avg = $_student->student->subject_average_score([$_subject->id, $_terms, 'O']);
                                    $_oral_percent = $_student->student->subject_score([$_subject->id, $_terms, 'O1']) !== null ? ($_oral_avg >= 0 ? number_format($_oral_avg, 2) : null) : null;
                                    // Output
                                    $_output_avg = $_student->student->subject_average_score([$_subject->id, $_terms, 'R']);
                                    $_output_percent = $_student->student->subject_score([$_subject->id, $_terms, 'R1']) !== null ? ($_output_avg >= 0 ? number_format($_output_avg, 2) : null) : null;
                                    // Examination
                                    $_exam_avg = $_student->student->subject_average_score([$_subject->id, $_terms, strtoupper($_terms)[0] . 'E']);
                                    $_exam_percent = $_student->student->subject_score([$_subject->id, $_terms, strtoupper($_terms)[0] . 'E1']) !== null ? number_format($_exam_avg, 2) : null;
                                    //$_exam_percent = $_exam_avg;
                                    // Lecture Grade
                                    $_lec_grade = $_student->student->lecture_grade([$_subject->id, $_terms]);
                                    $_lecture_grade = $_quiz_percent !== null && $_oral_percent !== null && $_output_percent !== null && $_exam_percent !== null ? number_format($_lec_grade, 2) : '';
                                    // Laboratory Grade
                                    $_lab_grade = $_student->student->laboratory_grade([$_subject->id, $_terms]);
                                    $_lab_grade = $_student->student->subject_score([$_subject->id, $_terms, 'A1']) !== null ? ($_lab_grade >= 0 ? number_format($_lab_grade, 2) : '') : '';
                                    // Course Outcome
                                    $_course_outcome_grade = $_student->student->subject_score([$_subject->id, $_terms, 'CO1']) !== null ? number_format($_student->student->subject_average_score([$_subject->id, $_terms, 'CO'], 2)) : '';
                                    //Total Final Grade
                                    $_period_final_grade = $_student->student->final_grade_v2($_subject->id, $_terms); // GET THE FINAL GRADE BASE THE TERMS
                                    $_point_grade = '';
                                    $_total_final_grade = '';

                                    // CHECK IF THE SUBJECT HAVE A LABORATORY GRADES
                                    if ($_subject->curriculum_subject->subject->laboratory_hours > 0) {
                                        $_period_final_grade = number_format($_period_final_grade, 2);
                                    } else {
                                        $_period_final_grade = $_lecture_grade !== '' ? number_format($_period_final_grade, 2) : '';
                                    }
                                    if ($_period_final_grade !== '') {
                                        $_point_grade = number_format($_student->student->percentage_grade(number_format($_period_final_grade, 2)), 2);
                                    } else {
                                        $_point_grade = $_quiz_percent !== null || $_oral_percent !== null || $_output_percent !== null || $_exam_percent !== null ? 'INC' : '';
                                    }
                                    // Computation of Total Final Grade for the Semester
                                    if ($_terms == 'finals' && $_subject->academic->id > 4) {
                                        if ($_period_final_grade !== '') {
                                            $midtermGrade = $_student->student->final_grade_v2($_subject->id, 'midterm') * 0.32;
                                            $finalsGrade = $_period_final_grade * 0.33;
                                            $coa = $_student->student->subject_average_score([$_subject->id, $_terms, 'CO']) * 0.35;
                                            $_total_final_grade = $midtermGrade + $finalsGrade + $coa;
                                            $_total_final_grade = number_format($_total_final_grade, 2);
                                        }
                                    }
                                    $_style = $_point_grade === 'INC' ? 'tr-incomplete' : '';
                                    $count += 1;
                                @endphp
                                <tr class="text-center {{ $_style }}">
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
                                        {{ $_quiz_percent }}
                                    </th>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <td>
                                            {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), 'O' . $i]) }}
                                        </td>
                                    @endfor
                                    <th>
                                        {{-- Oral Average --}}
                                        {{ $_oral_percent }}
                                    </th>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <td>
                                            {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), 'R' . $i]) }}
                                        </td>
                                    @endfor
                                    <th>
                                        {{-- Research Word Output Average --}}
                                        {{ $_output_percent }}
                                    </th>
                                    <td>
                                        {{ $_student->student->subject_score([$_subject->id, request()->input('_period'), strtoupper(request()->input('_period'))[0] . 'E1']) }}
                                    </td>
                                    <th>
                                        {{-- Examination Average --}}
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
                                        {{ $_period_final_grade }}
                                    </th>
                                    @if ($_subject->academic->id > 4)
                                        @if (request()->input('_period') == 'finals')
                                            <th>{{ $_course_outcome_grade }}
                                            </th>
                                            <th>{{ $_total_final_grade }}</th>
                                        @endif
                                    @endif
                                    <th>
                                        {{ $_point_grade }}
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
