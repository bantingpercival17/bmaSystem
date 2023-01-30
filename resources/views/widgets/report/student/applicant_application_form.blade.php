@extends('widgets.report.app_report_template')
@php
    $_form_number = $_account->course_id == 3 ? ' RG-02' : ' RG-01';
    $_department = $_account->course_id == 3 ? 'SENIOR HIGH SCHOOL' : 'COLLEGE';
@endphp
@section('title-report',
    $_form_number .
    ' - STUDENT REGISTRATION : ' .
    strtoupper(
    $_account->applicant->last_name .
    ',
    ' .
    $_account->applicant->first_name .
    ' ' .
    $_account->applicant->middle_name,
    ))
@section('form-code', $_form_number)
@section('content')
    <main class="content">
        <p class="h5 text-center m-0 p-0">STUDENT'S APPLICATION FORM</p>
        <p class="text-center fw-bolder m-0 p-0 mb-3"> <small>
                {{ $_department . ' - ' . $_account->course->course_name }}</small></p>

        <h6 for="" class="text-header">A. PERSONAL INFORMATION</h6>
        <table class="table-content" style="margin: 0px; padding:0">
            <tbody>
                <tr class="m-0">
                    <td colspan="2"><small>APPLICANT NO:</small>
                        <br><b>{{ $_account->applicant_number }}</b>
                    </td>
                    <td>
                        <small>APPLICATION DATE:</small> <br>
                        <b>{{ strtoupper(date('F j, Y', strtotime($_account->created_at))) }}</b>
                    </td>
                </tr>
                <tr class="">
                    <td colspan="2">
                        <small>APPLICANT NAME: </small> <br>
                        <b>{{ strtoupper($_account->applicant->last_name . ', ' . $_account->applicant->first_name . ' ' . $_account->applicant->middle_name) }}</b>
                    </td>
                    <td>
                        <small>BIRTH DATE:</small><br>
                        <b>{{ Str::upper(date('F j, Y', strtotime($_account->applicant->birthday))) }}</b>
                    </td>
                </tr>
                <tr class="m-0 p-0">
                    <td>
                        <small>BIRTH PLACE:</small> <br>
                        <b>{{ Str::upper($_account->applicant->birth_place) }}</b>
                    </td>
                </tr>
                <tr class="m-0 p-0">
                    <td>
                        <small>GENDER: </small> <br><b>MALE</b>
                    </td>
                    <td>
                        <small>HEIGHT: </small> <br>
                        <b>{{ $_account->applicant->height }} Ft/In </b>
                    </td>
                    <td>
                        <small>WEIGHT: </small> <br>
                        <b> -{{ $_account->applicant->weight }} Kg</b>
                    </td>
                    <td>
                        @php
                            $bmi = 0;
                            if ($_account->applicant->weight > 0 && $_account->applicant->height) {
                                $bmi = 0;
                            }
                        @endphp
                        <small>BMI: </small> <br>
                        <b> {{ $bmi }}</b>
                    </td>
                </tr>
                <tr class="m-0 p-0">
                    <td colspan="2"><small>RELIGION: </small> <br>
                        <b>{{ strtoupper($_account->applicant->religion) }}</b>
                    </td>
                    <td>
                        <small>CITIZENSHIP:</small> <br>
                        <b>{{ Str::upper($_account->applicant->nationality) }}</b>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table-content">
            <tbody>
                <tr>
                    <td colspan="3">
                        <small>CONTACT NUMBER: </small>
                        <br>
                        <b>{{ $_account->contact_number }}</b>
                    </td>
                    <td colspan="2"><small>EMAIL ADDRESS: </small><br>
                        <b>{{ $_account->email }}</b>
                    </td>
                </tr>
                <tr>
                    <td colspan=""><small>ADDRESS:</small>
            <tbody>
                <tr class="text-center">
                    <td><b>{{ Str::upper($_account->applicant->street) }}</td>
                    <td><b>{{ Str::upper($_account->applicant->barangay) }}</b></td>
                    <td><b>{{ Str::upper($_account->applicant->municipality) }}</b></td>
                    <td><b>{{ Str::upper($_account->applicant->province) }}</b></td>
                </tr>
                <tr class="text-center">
                    <td> <small>STREET</small></td>
                    <td><small>BARANGAY</small></td>
                    <td><small>MUNICIPALITY / CITY</small></td>
                    <td> <small>PROVINCE</small></td>
                </tr>
            </tbody>
        </table>
        <br>
        <h6 for="" class="text-header">B. PARENT'S INFORMATION</h6>
        <table class="table-content">
            <tbody>
                <tr>
                    <td colspan="2">
                        <small>FATHER NAME:</small><br>
                        <b>{{ strtoupper($_account->applicant->father_last_name . ', ' . $_account->applicant->father_first_name . ' ' . $_account->applicant->father_middle_name) }}</b>
                    </td>
                    <td style="margin: 0px; padding:0">
                        <small>CONTACT NUMBER:</small><br>
                        <b>{{ $_account->applicant->father_contact_number }}
                    </td>
                </tr>
                <tr style="margin: 0px; padding:0">
                    <td colspan="2">
                        <small>MOTHER'S MAIDEN NAME:</small>
                        <br>
                        <b>{{ strtoupper($_account->applicant->mother_last_name . ', ' . $_account->applicant->mother_first_name . ' ' . $_account->applicant->mother_middle_name) }}</b>
                    </td>
                    <td>
                        <small>CONTACT NUMBER:</small> <br>
                        <b>{{ $_account->applicant->mother_contact_number }}
                    </td>
                </tr>
            </tbody>
        </table>
        <h6 for="" class="text-header mt-3">C. SCHOOL ATTENDED</h6>
        <table class="table-content">

            @if ($_account->course_id != 3)
                <tbody>
                    <tr>
                        <td>
                            <small>ELEMENTARY SCHOOL</small><br>
                            <span>
                                <b> {{ $_account->applicant->elementary_school_name }}</b>
                            </span>
                        </td>
                        <td>
                            <small>SCHOOL ADDRESS</small><br>
                            <span>
                                <b> {{ $_account->applicant->elementary_school_address }}</b>
                            </span>
                        </td>
                        <td>
                            <small>Graduated Year</small><br>
                            <span>
                                <b>
                                    {{ strtoupper(date('F  Y', strtotime($_account->applicant->elementary_school_year))) }}</b>
                            </span>
                        </td>
                    </tr>
                    <tr>

                        <td>
                            <small>JUNIOR HIGH SCHOOL</small>
                            <br>
                            <span>
                                <b>
                                    {{ $_account->applicant->junior_high_school_name }}
                                </b>
                            </span>
                        </td>
                        <td>
                            <small>SCHOOL ADDRESS</small><br>
                            <span>
                                <b> {{ $_account->applicant->junior_high_school_address }}</b>
                            </span>
                        </td>
                        <td>
                            <small>Graduated Year</small><br>
                            <span>
                                <b>
                                    {{ strtoupper(date('F  Y', strtotime($_account->applicant->junior_high_school_year))) }}</b>
                            </span>
                        </td>
                    </tr>
                    <tr>

                        <td>
                            <small>SENIOR HIGH SCHOOL</small>
                            <br>
                            <b>
                                {{ $_account->applicant->senior_high_school_name }}
                            </b>
                        </td>
                        <td>
                            <small>SCHOOL ADDRESS</small><br>
                            <span>
                                <b> {{ $_account->applicant->senior_high_school_address }}</b>
                            </span>
                        </td>
                        <td>
                            <small>Graduated Year</small><br>
                            <span>
                                <b>
                                    {{ strtoupper(date('F  Y', strtotime($_account->applicant->senior_high_school_year))) }}</b>
                            </span>
                        </td>
                    </tr>
                </tbody>
            @else
                <tbody>
                    <tr>
                        <td>
                            <small>ELEMENTARY SCHOOL</small><br>
                            <span>
                                <b> {{ $_account->applicant->elementary_school_name }}</b>
                            </span>
                        </td>
                        <td>
                            <small>SCHOOL ADDRESS</small><br>
                            <span>
                                <b> {{ $_account->applicant->elementary_school_address }}</b>
                            </span>
                        </td>
                        <td>
                            <small>Graduated Year</small><br>
                            <span>
                                <b>
                                    {{ strtoupper(date('F  Y', strtotime($_account->applicant->elementary_school_year))) }}</b>
                            </span>
                        </td>
                    </tr>
                    <tr>

                        <td>
                            <small>JUNIOR HIGH SCHOOL</small>
                            <br>
                            <span>
                                <b>
                                    {{ $_account->applicant->junior_high_school_name }}
                                </b>
                            </span>
                        </td>
                        <td>
                            <small>SCHOOL ADDRESS</small><br>
                            <span>
                                <b> {{ $_account->applicant->junior_high_school_address }}</b>
                            </span>
                        </td>
                        <td>
                            <small>Graduated Year</small><br>
                            <span>
                                <b>
                                    {{ strtoupper(date('F  Y', strtotime($_account->applicant->junior_high_school_year))) }}</b>
                            </span>
                        </td>
                    </tr>

                </tbody>
            @endif
        </table>
        <div class="page-break"></div>
        <div class="applicant-documents mt-3">
            <h6 for="" class="text-header mt-3">D. REQUREID DOCUMENTS</h6>
            @foreach ($_account->empty_documents() as $docu)
                @php
                    $item = $docu->applicant_document;
                @endphp
                <div class="mt-5">
                    <div class="col-md-12">
                        <h5 class="fw-boldersss">{{ $docu->document_name }}</h5>
                        @if ($item)
                            <img src="{{ json_decode($item->file_links)[0] }}" alt="" width="100%" srcset=""
                                class="mb-3 mt-2">
                        @else
                        @endif

                    </div>
                </div>
                <div class="page-break"></div>
            @endforeach
        </div>
    </main>
@endsection
