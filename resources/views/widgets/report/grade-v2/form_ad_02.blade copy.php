@extends('widgets.report.grade-v2.report_layout_1')
@section('title-report', 'FORM AD 02 - GRADING SHEET : ' . $_subject->curriculum_subject->subject->subject_code)
@section('form-code', 'AD - 02')
@section('content')
    <div class="content">
        <h3 class="text-center"><b>REPORT OF GRADES</b></h3>
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
        <table class="table-2">
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
                    <td style="width: 40px; text-align: center;">STD NO.</td>
                    <td style="width: 180px;text-align: center;">COMPLETE NAME</td>
                    <td>PERCENT</td>
                    <td>RANGE</td>
                    <td>PERCENT</td>
                    <td>RANGE</td>
                </tr>
            </thead>
            <tbody>
                @if ($_students->count() > 0)
                    @foreach ($_students as $_key => $_student)
                        <tr>
                            <td class="text-center">{{ $_key + 1 }}</td>
                            <td class="text-center">
                                {{ $_student->student->account ? $_student->student->account->student_number : '' }}</td>
                            <td style="padding-left: 10px;">
                                {{ strtoupper($_student->student->last_name . ', ' . $_student->student->first_name) }}
                            </td>
                            <td class="text-center">
                                @php
                                    $_final = $_student->student->final_grade_v2($_subject->id, 'midterm');
                                @endphp
                                {{ $_final > 0 ? number_format($_final, 2) : '' }}
                            </td>
                            <td class="text-center">
                                <b>
                                    {{ $_final > 0 ? number_format($_student->student->percentage_grade(number_format($_final, 2)), 2) : '' }}</b>
                            </td>
                            <td class="text-center">
                                @php
                                    $_final = $_student->student->final_grade_v2($_subject->id, 'finals');
                                @endphp
                                {{ $_final !== 49 ? number_format($_final, 2) : '' }}
                            </td>
                            <td class="text-center">
                                <b>
                                    {{ $_final !== null ? number_format($_student->student->percentage_grade(number_format($_final, 2)), 2) : '' }}</b>
                            </td>
                            <td class="text-center fw-bolder">
                                <b>
                                    {{ $_final !== null ? ($_student->student->percentage_grade($_final) >= 5 ? 'FAILED' : 'PASSED') : '' }}
                                </b>

                            </td>
                        </tr>
                    @endforeach
                @endif

            </tbody>
        </table>


    </div>

@endsection
