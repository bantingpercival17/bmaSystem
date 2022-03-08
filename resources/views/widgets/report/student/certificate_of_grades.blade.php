@extends('widgets.report.grade.report_layout_1')
@php
$_form_number = 'AD-02a';
$_average = 0;
@endphp
@section('title-report', $_form_number . ' - STUDENT REGISTRATION : ' . strtoupper($_student->last_name . ', ' .
    $_student->first_name . ' ' . $_student->middle_name))
@section('form-code', $_form_number)
@section('content')
    <main class="content">
        <div class="form-certificate-of-grades">
            <h3 class="text-center">CERTIFICATE OF GRADES</h3>
            <br>
            <div class="student-information">
                <table class="form-rg-table">
                    <tbody>
                        <tr>
                            <td width="15%">
                                STUDENT'S NAME:
                            </td>
                            <td class="text-fill-in" width="40%"><b>{{ strtoupper($_student->last_name) }},
                                    {{ strtoupper($_student->first_name) }}
                                    {{ strtoupper($_student->middle_name[0]) }}.</b></td>
                            <td colspan="2">
                                YEAR LEVEL / SECTION:

                            </td>
                            <td class="text-fill-in" colspan="2"><b>{{ $_section->section_name }}</b></td>
                        </tr>
                        <tr>
                            <td>STUDENT NUMBER:</td>
                            <td class="text-fill-in"><b>{{ strtoupper($_student->account->student_number) }}</b></td>
                            <td>SCHOOL YEAR:</td>
                            <td class="text-fill-in"><b>{{ strtoupper($_section->academic->school_year) }}</b></td>
                            <td>TERM:</td>
                            <td class="text-fill-in"><b>{{ strtoupper($_section->academic->semester) }}</b></td>
                        </tr>

                    </tbody>
                    <tbody>
                        <tr>
                            <td width="10%">
                                COURSE:
                            </td>
                            <td class="text-fill-in" width="20%">
                                <b>{{ $_section->course->course_name }}</b>
                            </td>
                            <td colspan="2">


                            </td>
                            <td class="" colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="report-card">
                <table class="table-2">
                    <thead>
                        <tr>
                            <th rowspan="2" width="15%">COURSE CODE</th>
                            <th rowspan="2" width="45%">COURSE TITLE</th>
                            <th colspan="2">FINAL GRADE</th>
                            <th rowspan="2">REMARKS</th>
                        </tr>
                        <tr class="text-center">
                            <td>PERCENT</td>
                            <td>POINTS</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($_section->subject_class as $item)
                            @php
                                $_final_grade = number_format($_student->final_grade($item->id, 'finals'), 2);
                                $_final_grade = $_student->percentage_grade($_final_grade);
                                $_average += $_final_grade;
                            @endphp
                            <tr class="text-center">
                                <td><b>{{ $item->curriculum_subject->subject->subject_code }}</b></td>
                                <td><b>{{ $item->curriculum_subject->subject->subject_name }}</b></td>
                                <td> {{ number_format($_student->final_grade($item->id, 'finals'), 2) }}</td>
                                <td>
                                    {{ number_format($_student->percentage_grade(number_format($_student->final_grade($item->id, 'finals'), 2)), 2) }}
                                </td>

                                <td>
                                    {{ $_student->percentage_grade(number_format($_student->final_grade($item->id, 'finals'), 2)) >= 5? 'FAILED': 'PASSED' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="text-center">
                            <td colspan="2"><b>AVERAGE</b></td>
                            <td colspan="2">
                                <h3><b>{{ number_format($_average / count($_section->subject_class), 2) }}</b></h3>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="signatories">
                <br><br>
                <table class="table">
                    <tbody>
                        <tr>
                            <td colspan="2">
                                Verified By:
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td><b>{{ strtoupper(Auth::user()->staff->academic_head($_section->course_id)) }}</b></td>
                            <td> <b>{{ strtoupper(Auth::user()->staff->registrar()) }}</b></td>
                        </tr>
                        <tr class="text-center">
                            <td><small>Department Head</small> </td>
                            <td><small>College Registrar</small> </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr class="text-center">
                            <td colspan="2"> Approved By:</td>
                        </tr>

                        <tr class="text-center">
                            <td colspan="2"> <b>Capt. Maximo M. Pesta&ntilde;o</b></td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2"><small>School Director / Dean of Maritime Studies</small></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
