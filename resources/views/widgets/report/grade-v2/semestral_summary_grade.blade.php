@extends('widgets.report.layout_report')
{{-- @extends('widgets.report.main-report-template') --}}
@section('title-report', ' SUMMARY GRADES : ' . $course->course_name)
@php
    $_year_level = $level == '4' ? 'First Year' : '';
    $_year_level = $level == '3' ? 'Second Year' : $_year_level;
    $_year_level = $level == '2' ? 'Third Year' : $_year_level;
    $_year_level = $level == '1' ? 'Fourth Year' : $_year_level;
@endphp
@section('content')
    <div class="page-content">
        @foreach ($curriculum as $curriculum)
            @if (count($curriculum->student_enrolled) > 0)
                <header>
                    <label for="" class="form-code">BMA FORM </label>
                    <div class="text-center">
                        <img src="{{ public_path() . '/assets/image/report-header.png' }}" alt="page-header">
                    </div>
                </header>
                <div class="page-content">
                    <div class="content">
                        <div class="summary-grade-header">
                            <h3 class="text-center" style="margin:0px;"><b>REPORT OF FINAL GRADES</b></h3>
                            <h4 class="text-center" style="margin:0px;">
                                {{ strtoupper($_year_level) }}</h4>
                            <h4 class="text-center" style="margin:0px;">
                                {{ Auth::user()->staff->current_academic()->semester }},
                                A.Y. {{ strtoupper(Auth::user()->staff->current_academic()->school_year) }}
                            </h4>
                            <small><b>{{ str_replace('BS', 'BACHELOR OF SCIENCE IN', $course->course_name) }}</b></small><br>
                            <small><b>{{ $curriculum->curriculum_name }}</b></small>
                        </div>

                        <table class="table-summary-grade">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th width="20%">NAMES</th>
                                    @foreach ($curriculum->subject([$course->id, $level, Auth::user()->staff->current_academic()->semester])->get() as $_subject)
                                        <th style="width: auto;">
                                            {{ $_subject->subject->subject_code }}
                                        </th>
                                        <td class="text-center">UNITS</td>
                                    @endforeach
                                    <th>REMARKS</th>
                                    <th>GWA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($course->student_enrollment_list([$level, $curriculum->id])->get() as $_key => $_data)
                                    <tr>
                                        <td width="2%" class="text-center">{{ $_key + 1 }}
                                        </td>
                                        <th style="text-align: left; padding-left:10px;">
                                            {{ strtoupper($_data->student->last_name . ', ' . $_data->student->first_name . ' ' . $_data->student->middle_name) }}
                                        </th>
                                        @php
                                            $_total_units = 0;
                                            $total_units = 0;
                                            $total_points = 0;
                                            $_average = 0;
                                        @endphp
                                        @foreach ($curriculum->subject([$course->id, $level, Auth::user()->staff->current_academic()->semester])->get() as $_subject)
                                            <th>
                                                @php
                                                    if (!is_string($_data->student->student_final_subject_grade($_subject))) {
                                                        if (!in_array($_subject->subject->subject_code, ['BRDGE', 'P.E. 1', 'P.E. 2', 'P.E. 3', 'P.E. 4'])) {
                                                            $points = $_data->student->student_final_subject_grade($_subject);
                                                            $total_units += $_subject->subject->units; // Units
                                                            $percentage = $_subject->subject->units * $points; // Get the Percentage of Unit and Points
                                                            $total_points += $percentage; // Sum the Total Percentage
                                                        }
                                                    }
                                                    $_total_units += $_subject->subject->units;
                                                @endphp
                                                {{ $_data->student->student_final_subject_grade($_subject) }}
                                            </th>
                                            <td class="text-center">{{ $_subject->subject->units }}</td>
                                        @endforeach
                                        <th>{{ $_total_units }} UNITS</th>
                                        <th>
                                            @if ($total_points != 0)
                                                <b>{{ number_format($total_points / $total_units, 4) }}</b>
                                            @endif
                                        </th>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <small class="summary-grade-header">TOTAL NO OF ENROLLEES:
                            <b>{{ $course->student_enrollment_list([$level, $curriculum->id])->get()->count() }}</b></small>
                        <div class="signatories">
                            <br><br>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>PREPARED BY:</td>
                                        <td>CHECKED AND VERIFIED BY:</td>
                                        <td>NOTED BY:</td>
                                    </tr>
                                    <tr class="text-center">

                                        <td style="height: 10%">
                                           
                                        </td>
                                        <td>
                                           
                                        </td>
                                        <td>
                                           
                                        </td>

                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-center">
                                            <b>{{ strtoupper(Auth::user()->staff->registrar()) }}</b>
                                            <br>
                                            <small>College Registrar</small>
                                        </td>
                                        <td class="text-center">
                                            <b>{{ strtoupper(Auth::user()->staff->academic_head($course->id)) }}</b>
                                            <br>
                                            <small>Department Head</small>
                                        </td>
                                        <td colspan="2">
                                            <b>Capt. Maximo M. Pesta&ntilde;o</b>
                                            <br>
                                            <small>Dean of Maritime Studies</small>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

                <div class="page-break"></div>
            @endif
        @endforeach
    </div>

@endsection
@section('style')
    <style>
        .image-signature {
            width: 30%;
        }

        .table {
            page-break-before: auto;
        }

        .table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            /*  border: 1px solid #ddd;
                                                            border: 1px solid #ddd; */
        }

        .table td,
        .table th {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 10px;
            /*  border: 1px solid rgb(126, 126, 126); */
            font-size: 14px;

        }
    </style>
@endsection
