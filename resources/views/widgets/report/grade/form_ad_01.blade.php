@extends('widgets.report.grade.report_layout_1')
@section('title-report', 'FORM AD 01 - GRADING SHEET : ' . $_subject->curriculum_subject->subject->subject_code)
@section('form-code', 'AD - 01')
@section('content')
    <div class="content">
        <table class="table">
            <tbody>
                <tr>
                    <td><small>SUBJECT :</small>
                        <span><b>{{ $_subject->curriculum_subject->subject->subject_code }}</b></span>
                    </td>
                    <td style="width: 55%"></td>
                    <td><small>SCHOOL YEAR:</small>
                        <span><b>{{ $_subject->academic->school_year . ' | ' . $_subject->academic->semester }}</b></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <small>COURSE:</small>
                        <span><b>{{ $_subject->curriculum_subject->course->course_code }}</b></span>
                    </td>
                    <td style="width: 55%" class="text-center">
                        <b> OFFICIAL CLASS RECORD</b>
                    </td>
                    <td>
                        <small>PERIOD</small>
                        <span><b>{{ strtoupper(request()->input('_period')) }}</b></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table-2">
            <thead>
                <tr class="text-center">
                    <th colspan="3" class="fixed">NAME OF MIDSHIPMAN</th>
                    <th colspan="11" class="bg-success  table-bordered">QUIZZES</th>
                    <th colspan="6" class="bg-success  table-bordered">ORAL</th>
                    <th colspan="11" class="bg-success  table-bordered">R W - OUTPUT</th>
                    <th colspan="2" class="bg-success  table-bordered">
                        {{ strtoupper(request()->input('_period')) }} EXAMS</th>
                    <th>LECT <small>GRADE</small></th>
                    <th colspan="10">Scientific and Technical Experiments Demonstrations of Competencies Acquired</th>
                    <th>LAB GRADE</th>
                    <th>{{ strtoupper(request()->input('_period')) }} GRADE</th>
                    <th>POINT GRADE</th>
                </tr>
                <tr class="text-center">
                    <td style="width: 20px; text-align: center;">NO</td>
                    <td style="width: 40px; text-align: center;">STD NO.</td>
                    <td style="width: 150px;text-align: center;">COMPLETE NAME</td>
                    @for ($i = 1; $i <= 10; $i++)
                        <td style="width: 20px;">Q{{ $i }}</td>
                    @endfor
                    <th style="width: 30px; text-align: center;"><b>15%</b></th>
                    @for ($i = 1; $i <= 5; $i++)
                        <td style="width: 20px;">O{{ $i }}</td>
                    @endfor
                    <th style="width: 30px; text-align: center;"><b>15%</b></th>
                    @for ($i = 1; $i <= 10; $i++)
                        <th style="width: 20px;">R{{ $i }}</th>
                    @endfor
                    <th style="width: 30px; text-align: center;"><b>15%</b></th>
                    <th>{{ strtoupper(request()->input('_period'))[0] }}E</th>
                    <th style="width: 30px; text-align: center;"><b>55%</b></th>
                    <th style="width: 40px; text-align: center;"><b>40%</b></th>
                    @for ($i = 1; $i <= 10; $i++)
                        <th>A{{ $i }}</th>
                    @endfor
                    <th>60%</th>
                    <th>(FG)</th>
                    <th>(GP)</th>
                </tr>
            </thead>
            <tbody>
                @if ($_students->count() > 0)
                    @foreach ($_students as $_key => $_student)
                        <tr class="text-center">
                            <td>{{ $_key + 1 }}</td>
                            <td>{{ $_student->account->student_number }}</td>
                            <td>{{ strtoupper($_student->last_name . ', ' . $_student->first_name) }}</td>
                            @for ($i = 1; $i <= 10; $i++)
                                <td>
                                    {{ $_student->subject_score([$_subject->id, request()->input('_period'), 'Q' . $i]) }}
                                </td>
                            @endfor
                            <th>
                                @php
                                    $_quiz_avg = $_student->subject_average_score([$_subject->id, request()->input('_period'), 'Q']);
                                @endphp
                                {{ $_quiz_avg > 0 ? number_format($_quiz_avg, 2) : '' }}
                            </th>
                            @for ($i = 1; $i <= 5; $i++)
                                <td>
                                    {{ $_student->subject_score([$_subject->id, request()->input('_period'), 'O' . $i]) }}
                                </td>
                            @endfor
                            <th>
                                @php
                                    $_oral_avg = $_student->subject_average_score([$_subject->id, request()->input('_period'), 'O']);
                                @endphp
                                {{ $_oral_avg > 0 ? number_format($_oral_avg, 2) : '' }}
                            </th>
                            @for ($i = 1; $i <= 10; $i++)
                                <td>
                                    {{ $_student->subject_score([$_subject->id, request()->input('_period'), 'R' . $i]) }}
                                </td>
                            @endfor
                            <th>
                                @php
                                    $_output_avg = $_student->subject_average_score([$_subject->id, request()->input('_period'), 'R']);
                                @endphp
                                {{ $_output_avg > 0 ? number_format($_output_avg, 2) : '' }}
                            </th>
                            <td>
                                {{ $_student->subject_score([$_subject->id, request()->input('_period'), strtoupper(request()->input('_period'))[0] . 'E1']) }}
                            </td>
                            <th>
                                @php
                                    $_exam_avg = $_student->subject_average_score([$_subject->id, request()->input('_period'), strtoupper(request()->input('_period'))[0] . 'E']);
                                @endphp
                                {{ $_exam_avg > 0 ? number_format($_exam_avg, 2) : '' }}

                            </th>
                            <th>
                                @php
                                    $_lec_grade = $_student->lec_grade([$_subject->id, request()->input('_period')]);
                                @endphp
                                {{ $_lec_grade > 0 ? number_format($_lec_grade, 2) : '' }}
                            </th>
                            @for ($i = 1; $i <= 10; $i++)
                                <td>
                                    {{ $_student->subject_score([$_subject->id, request()->input('_period'), 'A' . $i]) }}
                                </td>

                            @endfor
                            <th>
                                @php
                                    $_lab_grade = $_student->lab_grade([$_subject->id, request()->input('_period')]);
                                @endphp
                                {{ $_lab_grade > 0 ? number_format($_lab_grade, 2) : '' }}
                            </th>
                            <th>
                                @php
                                    $_final = $_student->final_grade($_subject->id, request()->input('_period'));
                                @endphp
                                {{ $_final > 0 ? number_format($_final, 2) : '' }}
                            </th>
                            <th>
                                {{ $_final > 0 ? number_format($_student->percentage_grade(number_format($_final, 2)), 2) : '' }}
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

@endsection
