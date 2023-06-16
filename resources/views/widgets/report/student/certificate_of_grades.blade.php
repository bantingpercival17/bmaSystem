@extends('widgets.report.grade.report_layout_1')
@php
    $_form_number = 'AD-02a';
    $_average = 0;
    $_total_percentage = 0;
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
                <table class="subject-list-table">
                    <thead>
                        <tr>
                            <th rowspan="2" width="15%">COURSE CODE</th>
                            <th rowspan="2" width="45%">DESCRIPTIVE TITLE</th>
                            <th colspan="2">FINAL GRADE</th>
                            <th rowspan="2">REMARKS</th>
                        </tr>
                        <tr class="text-center">
                            {{-- <td>PERCENT</td> --}}
                            <td>GRADE</td>
                            <td>UNITS</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $_subject_count = 0;
                            $_total_units = 0;
                        @endphp
                        @foreach ($_section->subject_class as $item)
                            @php
                                $_percentage = 0;
                                $_status = $_student->enrollment_status->bridging_program == 'with' || $item->curriculum_subject->subject->subject_code != 'BRDGE';
                                
                                // TODO: Need to delete this Code
                                $term = $_section->academic_id = 5 ? 'midterm' : 'finals';
                                $_final_grade = number_format($_student->final_grade_v2($item->id, 'finals'), 2); // Grade
                                $_point = $_student->percentage_grade($_final_grade); // Points
                                $_average = $_status ? $_average + $_final_grade : $_average;
                                $_subject_count = $_status ? $_subject_count + 1 : $_subject_count; // Count the Subjects
                                
                                if (!in_array($item->curriculum_subject->subject->subject_code, ['BRDGE', 'P.E. 1', 'P.E. 2', 'P.E. 3', 'P.E. 4'])) {
                                    $_total_units += $item->curriculum_subject->subject->units; // Units
                                    $_percentage = $item->curriculum_subject->subject->units * $_point; // Get the Percentage of Unit and Points
                                    $_total_percentage += $_percentage; // Sum the Total Percentage
                                }
                                
                            @endphp
                            @if ($_status)
                                <tr class="text-center">
                                    <td><b>{{ $item->curriculum_subject->subject->subject_code }}</b></td>
                                    <td><b>{{ $item->curriculum_subject->subject->subject_name }}</b></td>
                                    <td>
                                        {{ number_format($_point, 2) }}

                                    </td>
                                    <td>{{ $item->curriculum_subject->subject->units }}</td>
                                    {{-- <td> {{ number_format($_student->final_grade($item->id, 'finals'), 2) }}</td> --}}


                                    <td>
                                        {{ $_point >= 5 ? 'FAILED' : 'PASSED' }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        {{-- <tr class="text-center">
                            <td><b>NROTC 1</b></td>
                            <td><b>NAVAL RESERVE 1</b></td>
                            <td>95.21</td>
                            <td>1.50</td>
                            <td>PASSED</td>
                        </tr> --}}
                    </tbody>
                    <tfoot>
                        <tr class="text-center">
                            <td colspan="2"><b>GENERAL WEIGHTED AVERAGE</b></td>
                            <td colspan="2">
                                <h3><b>{{ number_format($_total_percentage / $_total_units, 2) }}</b></h3>
                                {{-- <h3><b>{{ number_format(($_average + 1.5) / $_subject_count, 2) }}</b></h3> --}}
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
                            <td>
                                <img class="image-signature"
                                    src="{{ public_path() . '/assets/img/signature/' . Auth::user()->staff->academic_head_signature($_section->course_id) . '.png' }}"
                                    alt="department-head">
                            </td>
                            <td>
                                <img class="image-signature"
                                    src="{{ public_path() . '/assets/img/signature/' . Auth::user()->staff->department_head_signature('REGISTRAR') . '.png' }}"
                                    alt="department-head">
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td class="text-center">
                                <b>{{ strtoupper(Auth::user()->staff->academic_head($_section->course_id)) }}</b>
                                <br>
                                <small>Department Head</small>
                            </td>
                            <td class="text-center">
                                <b>{{ strtoupper(Auth::user()->staff->registrar()) }}</b>
                                <br>
                                <small>College Registrar</small>
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr class="text-center">
                            <td colspan="2"> Approved By:</td>

                        </tr>

                        <tr class="text-center">
                            <td colspan="2">
                                <br><br>
                                <img class="image-signature"
                                    src="{{ public_path() . '/assets/img/signature/' . Auth::user()->staff->dean_signature('Administrative') . '.png' }}"
                                    alt="department-head">
                                <br>
                                <b>Capt. Maximo M. Pesta&ntilde;o</b>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2"><small>Dean of Maritime Studies</small></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
@endsection
