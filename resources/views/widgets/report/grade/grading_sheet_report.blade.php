@extends('widgets.report.grade.report_layout_1')
@section('title-report', 'GRADING SHEET : ' . $_subject->curriculum_subject->subject->subject_code)
@section('form-code', 'AD - 01')
@section('content')
    <div class="content">
        <table class="table-1">
            <tbody>
                <tr>
                    <td><small>SUBJECT :</small>
                        <span><b>{{ $_subject->curriculum_subject->subject->subject_code }}</b></span>
                    </td>
                    <td></td>
                    <td><small>SCHOOL YEAR:</small>
                        <span><b>{{ $_subject->academic->school_year . ' | ' . $_subject->academic->semester }}</b></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <small>COURSE:</small>
                        <span><b>{{ $_subject->curriculum_subject->course->course_code }}</b></span>
                    </td>
                    <td>
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
                    <th colspan="2" class="fixed">NAME OF MIDSHIPMAN</th>
                    <th colspan="11" class="bg-success  table-bordered">QUIZZES</th>
                    <th colspan="6" class="bg-success  table-bordered">ORAL</th>
                    <th colspan="11" class="bg-success  table-bordered">R W - OUTPUT</th>
                    <th colspan="3" class="bg-success  table-bordered">
                        {{ strtoupper(request()->input('_period')) }}</th>
                </tr>
                <tr class="text-center">
                    <th>NO.</th>
                    <th style="width: 200px">COMPLETE NAME</th>
                    @for ($i = 1; $i <= 10; $i++)
                        <th>Q{{ $i }}</th>
                    @endfor
                    <th>QT(15%)</th>
                    @for ($i = 1; $i <= 5; $i++)
                        <th>O{{ $i }}</th>
                    @endfor
                    <th>OT(15%)</th>
                    @for ($i = 1; $i <= 10; $i++)
                        <th>R{{ $i }}</th>
                    @endfor
                    <th>RW(15%)</th>
                    <th>EXAM</th>
                    <th>E(55%)</th>
                    <th>FINAL GRADE</th>
                </tr>
            </thead>
            <tbody>
                @if ($_students->count() > 0)
                    @foreach ($_students as $_key => $_student)
                        <tr>
                            <td>{{ $_key + 1 }}</td>
                            <td>{{ strtoupper($_student->last_name . ', ' . $_student->first_name) . ' - ' . $_student->account->student_number }}
                            </td>
                            @for ($i = 1; $i <= 10; $i++)
                                @php
                                    $_score = $_student->subject_score([$_subject->id, request()->input('_period'), 'Q' . $i]);
                                    $_score = $_score ? $_score->score : '';
                                @endphp
                                <td class="text-center">
                                    {{ $_score }}
                                </td>
                            @endfor
                            <th>
                                @php
                                    $_quiz_avg = $_student->subject_average_score([$_subject->id, request()->input('_period'), 'Q']) * 0.15;
                                @endphp
                                {{ number_format($_quiz_avg, 2) }}
                            </th>
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $_score = $_student->subject_score([$_subject->id, request()->input('_period'), 'O' . $i]);
                                    $_score = $_score ? $_score->score : '';
                                @endphp
                                <td class="text-center table-bordered">
                                    {{ $_score }}
                                </td>
                            @endfor
                            <th>
                                @php
                                    $_oral_avg = $_student->subject_average_score([$_subject->id, request()->input('_period'), 'O']) * 0.15;
                                @endphp
                                {{ number_format($_oral_avg, 2) }}
                            </th>
                            @for ($i = 1; $i <= 10; $i++)
                                @php
                                    $_score = $_student->subject_score([$_subject->id, request()->input('_period'), 'R' . $i]);
                                    $_score = $_score ? $_score->score : '';
                                @endphp
                                <td class="text-center table-bordered">
                                    {{ $_score }}
                                </td>
                            @endfor
                            <th>
                                @php
                                    $_output_avg = $_student->subject_average_score([$_subject->id, request()->input('_period'), 'R']) * 0.15;
                                @endphp
                                {{ number_format($_output_avg, 2) }}
                            </th>
                            <td>
                                @php
                                    $_score = $_student->subject_score([$_subject->id, request()->input('_period'), 'E1']);
                                    $_score = $_score ? $_score->score : '';
                                @endphp
                                {{ $_score }}
                            </td>
                            <th>
                                @php
                                    $_exam_avg = $_student->subject_average_score([$_subject->id, request()->input('_period'), 'E']) * 0.55;
                                @endphp
                                {{ number_format($_exam_avg, 2) }}
                            </th>
                            <th>
                                @php
                                    $_total = $_quiz_avg + $_oral_avg + $_output_avg + $_exam_avg;
                                @endphp
                                {{ number_format($_total, 2) }}
                            </th>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">No Students</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

@endsection
