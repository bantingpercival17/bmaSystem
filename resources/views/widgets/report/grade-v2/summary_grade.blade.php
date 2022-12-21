@extends('widgets.report.grade-v2.report_layout_1')
@section('title-report', ' SUMMARY GRADES : ' . $_course->course_name)
@php
    $_year_level = $_level == '4' ? 'First Year' : '';
    $_year_level = $_level == '3' ? 'Second Year' : $_year_level;
    $_year_level = $_level == '2' ? 'Third Year' : $_year_level;
    $_year_level = $_level == '1' ? 'Fourth Year' : $_year_level;
@endphp
@section('form-code', '')
@section('content')
    <div class="content" {{-- style="margin-bottom: 5%;" --}}>
        <h3 class="text-center">REPORT OF FINAL GRADES</h3>
        <h4 class="text-center">{{ $_year_level . ', ' . Auth::user()->staff->current_academic()->semester }}
        </h4>
        <h5 class="text-center">A.Y.
            {{ strtoupper(Auth::user()->staff->current_academic()->school_year) }}</h5>
        <br>
        @foreach ($_curriculum as $curriculum)
            <small><b>{{ $_course->course_name }}</b></small>
            <table class="table-2">
                <thead>
                    <tr>
                        <th></th>
                        <th width="15%">NAMES</th>
                        @foreach ($curriculum->curriculum->subject([$_course->id, $_level, Auth::user()->staff->current_academic()->semester])->get() as $_subject)
                            <th>
                                {{ $_subject->subject->subject_code }}
                            </th>
                            <td class="text-center">UNITS</td>
                        @endforeach
                        <th>REMARKS</th>
                        <th>GEN AVERAGE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($_course->student_list as $_key => $_data)
                        <tr>
                            <td width="2%" class="text-center">{{ $_key + 1 }}
                            </td>
                            <th style="text-align: left; padding-left:10px;">
                                {{ strtoupper($_data->last_name . ', ' . $_data->first_name . ' ' . $_data->middle_name) }}
                            </th>
                            @php
                                $_total_units = 0;
                                $_average = 0;
                            @endphp
                            @foreach ($curriculum->curriculum->subject_lists([$_course->id, $_level, Auth::user()->staff->current_academic()->semester])->get() as $_subject)
                                @php
                                    $_subject_class = $_subject->curriculum_subject_class($_data->section_id);
                                    if ($_subject_class) {
                                        if ($_subject_class->grade_final_verification) {
                                            $_final_grade = number_format($_data->student->final_grade_v2($_subject_class->id, 'finals'), 2);
                                            $_final_grade = number_format($_data->student->percentage_grade($_final_grade), 2);
                                    
                                            if ($_subject->subject->subject_code == 'BRDGE') {
                                                $_final_grade = $_data->student->enrollment_status->bridging_program == 'with' ? $_final_grade : '';
                                            } else {
                                                $_final_grade = $_final_grade;
                                            }
                                        } else {
                                            $_final_grade = '-';
                                        }
                                    } else {
                                        $_final_grade = '';
                                    }
                                    //$_final_grade = $_subject_section ? $_subject_section->id : '-';
                                    $_total_units += $_subject->subject->units;
                                @endphp
                                <th>
                                    {{ $_final_grade }}
                                </th>
                                <td class="text-center"> {{ $_subject->subject->units }}</td>
                            @endforeach
                            <th>{{ $_total_units }} UNITS</th>
                            <th></th>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="page-break"></div>
        @endforeach
    </div>
    {{-- {{ $_course->student_list}} --}}
@endsection
