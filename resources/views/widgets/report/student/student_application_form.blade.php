@extends('widgets.report.grade.report_layout_1')
@php
    $_form_number = $_enrollment_assessment->course_id == 3 ? ' RG-02' : ' RG-01';
    $_department = $_enrollment_assessment->course_id == 3 ? 'SENIOR HIGH SCHOOL' : 'COLLGE';
@endphp
@section('title-report', $_form_number . ' - STUDENT REGISTRATION : ' . strtoupper($_student->last_name . ', ' .
    $_student->first_name . ' ' . $_student->middle_name))
@section('form-code', $_form_number)
@section('content')
    <main class="content">
        <h3 class="text-center">STUDENT'S APPLICATION FORM</h3>
        <small></small>
        <h5 class="text-center">
            {{ $_department . ' - ' . $_enrollment_assessment->course->course_name }}
        </h5>
        <br>
        <p class="title-header"><b>| PERSONAL INFORMATION</b></p>
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2"><small>APPLICANT NO:</small>
                        <b>{{ $_student->applicant ? $_student->applicant->applicant_number : '-' }}</b>
                    </td>
                    <td colspan="2"> <small>APPLICATION DATE:</small>
                        <b>{{-- {{ strtoupper(date('F j, Y', strtotime($_student->created_at))) }} --}}-</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <small>APPLICANT NAME: </small>
                        <b>{{ strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name) }}</b>
                    </td>
                    <td colspan="2"><small>BIRTH DATE:</small>
                        <b>{{ Str::upper(date('F j, Y', strtotime($_student->birthday))) }}</b>
                    </td>
                </tr>
                <tr>

                    <td> <small>BIRTH PLACE:</small> <b>{{ Str::upper($_student->birth_place) }}</b></td>
                </tr>
                <tr>
                    <td><small>GENDER: </small><b>{{ $_student->sex }}</td>
                    <td><small>HEIGHT: </small><b> {{ $_student->height ?: '-' }} CM</td>
                    <td><small>WEIGHT: </small><b> {{ $_student->weight ?: '-' }} POUND/S</td>
                    @php
                        $_cm = $_student->height;
                        $_kg = $_student->weight;
                        $_bmi = '';
                        if ($_cm > 0 && $_kg > 0) {
                            $_cm *= 0.01;
                            $_kg *= 0.453592;
                            $_height = $_cm * $_cm;
                            $_bmi = number_format($_kg / $_height, 2);
                        }
                        
                    @endphp
                    <td><small>BMI: </small><b>{{ $_bmi }}</td>
                    {{--  <td>
                        @php
                            $bmi = '-';
                            if ($_student->weight > 0 && $_student->height) {
                                $bmi = 0;
                            }
                        @endphp
                        <small>BMI: </small><b> {{ $bmi }}</b>
                    </td> --}}
                </tr>
                <tr>
                    <td colspan="2"><small>RELIGION: </small><b>{{ strtoupper($_student->religion) }}</td>
                    <td><small>CITIZENSHIP:</small> <b>{{ Str::upper($_student->nationality) }}</b></td>
                </tr>
            </tbody>

        </table>
        <br>
        <p class="title-header"><b>| CONTACT DETAILS</b></p>
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="3">CONTACT NUMBER: <b>{{ $_student->contact_number }}</td>
                    <td colspan="2">EMAIL ADDRESS: <b>{{ $_student->account->personal_email }}</td>
                </tr>
                <tr>
                    <td colspan=""><small>ADDRESS:</small>
            <tbody>
                <tr class="text-center">
                    <td><b>{{ Str::upper($_student->street) }}</td>
                    <td><b>{{ Str::upper($_student->barangay) }}</b></td>
                    <td><b>{{ Str::upper($_student->municipality) }}</b></td>
                    <td><b>{{ Str::upper($_student->province) }}</b></td>
                </tr>
                <tr class="text-center">
                    <td> <small>STREET</small></td>
                    <td><small>BARANGAY</small></td>
                    <td><small>MUNICIPALITY / CITY</small></td>
                    <td> <small>PROVINCE</small></td>
                </tr>
            </tbody>

            </td>
            </tr>

            </tbody>
        </table>
        <br>
        <p class="title-header"><b>| PARENT'S INFOMATION</b></p>
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2">
                        <small>FATHER NAME:</small>
                        <b>{{ strtoupper($_student->parent_details->father_last_name . ', ' . $_student->parent_details->father_first_name . ' ' . $_student->parent_details->father_middle_name) }}</b>
                    </td>
                    <td><small>CONTACT NUMBER:</small> <b>{{ $_student->parent_details->father_contact_number }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <small>MOTHER'S MAIDEN NAME:</small>
                        <b>{{ strtoupper($_student->parent_details->mother_last_name . ', ' . $_student->parent_details->mother_first_name . ' ' . $_student->parent_details->mother_middle_name) }}</b>
                    </td>
                    <td><small>CONTACT NUMBER:</small> <b>{{ $_student->parent_details->mother_contact_number }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <p class="title-header"><b>| SCHOOL ATTENDED</b></p>
        <br>
        <table class="table">
            @if ($_enrollment_assessment->course_id != 3)
                <tbody>
                    @foreach ($_student->educational_background as $_data)
                        <tr>
                            <td colspan="2"><b>{{ strtoupper($_data->school_level) }}</b></td>
                        </tr>
                        <tr>
                            <td width="15%"><small>SCHOOL NAME:</small>
                            </td>
                            <td class="text-fill" width="60%">
                                <b>{{ strtoupper($_data->school_name) }}</b>
                            </td>
                            <td width="3%"><small>AY:</small></td>
                            <td class="text-fill-in"><b>{{ $_data->graduated_year }}</b></td>
                        </tr>
                        <tr>
                            <td width="20%"><small>SCHOOL ADDRESS</small></td>
                            <td>{{ $_data->school_address }}</td>
                        </tr>
                    @endforeach



                </tbody>
            @else
                <tbody>
                    @foreach ($_student->educational_background as $_data)
                        <tr>
                            @if (strtoupper($_data->school_level) != 'SENIOR HIGH SCHOOL')
                                <td colspan="2"><small>{{ strtoupper($_data->school_level) }}:</small>
                                    <b>{{ strtoupper($_data->school_name) }}</b>
                                </td>
                                <td><small>AY:</small> </td>
                                <td class="text-fill-in"><b>{{ $_data->graduated_year }}</b></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
    </main>
@endsection
