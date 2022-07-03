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
    ),)
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
            {{-- @foreach ($_account->applicant_documents as $item)
                <div class="document">
                    <span class="mt-3">
                        <b>{{ $item->document->document_name }}</b>
                    </span>
                    <img src="{{ json_decode($item->file_links)[0] }}" alt="" width="100%" srcset=""
                        class="mb-3 mt-2">

                </div>
                <div class="page-break"></div>
            @endforeach --}}
            @foreach ($_account->empty_documents() as $docu)
                @php
                    $item = $docu->applicant_document;
                @endphp
                <div class="mt-5">
                    <div class="col-md-12">
                        <h5 class="fw-boldersss">{{ $docu->document_name }}</h5>
                        
                        <img src="{{ json_decode($item->file_links)[0] }}" alt="" width="100%" srcset=""
                            class="mb-3 mt-2">
                    </div>
                   {{--  @if ($item)
                        @if ($item->is_approved == null)
                            <form class="row" action="{{ route('document-verification') }}">
                                <div class="col-md-8">
                                    <input type="hidden" name="_document" value="{{ base64_encode($item->id) }}">
                                    <input type="text" class="form-control form-control-sm rounded-pill mt-2"
                                        name="_comment" placeholder="Comment!" required>
                                </div>
                                <div class="col-md">
                                    <a href="{{ route('document-verification') }}?_document={{ base64_encode($item->id) }}&_verification_status=1"
                                        class="mt-2 btn btn-outline-primary btn-sm rounded-pill " data-bs-toggle="tooltip"
                                        title="" data-bs-original-title="Approved Document">
                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M8.43994 12.0002L10.8139 14.3732L15.5599 9.6272" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                    <button type="submit" class=" mt-2 btn btn-outline-danger btn-sm rounded-pill "
                                        data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Disapprove Document">
                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.3955 9.59497L9.60352 14.387" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                            <path d="M14.3971 14.3898L9.60107 9.59277" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                    <a class="btn btn-outline-info btn-sm rounded-pill btn-form-document mt-2"
                                        data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                        data-document-url="{{ json_decode($item->file_links)[0] }}"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="View Image">
                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                    </a>
                                </div>
                            </form>
                        @endif

                        @switch($item->is_approved)
                            @case(1)
                                <div class="row mt-2">
                                    <div class="col-md-8">
                                        <h6 class="fw-bolder text-primary">DOCUMENT APPROVED</h6>
                                        <span>
                                            <small>APPROVED DATE:</small>
                                            <span role="button" data-bs-toggle="popover" data-trigger="focus" class="fw-bolder"
                                                title="APPROVED DETAILS"
                                                data-bs-content="Approved By: {{ $item->staff ? $item->staff->user->name : '-' }} Approved Date: {{ $item->created_at->format('F d,Y') }}">{{ $item->created_at->format('F d,Y') }}</span>
                                        </span>
                                    </div>
                                    <div class="col-md">
                                        <a class="btn btn-outline-info btn-sm rounded-pill btn-form-document mt-2 w-100"
                                            data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                            data-document-url="{{ json_decode($item->file_links)[0] }}">
                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            @break

                            @case(2)
                                <div class="row mt-2">
                                    <div class="col-md-8">
                                        <h6 class="fw-bolder text-danger">DOCUMENT DISAPPROVED</h6>
                                        <span>
                                            <small>REMARKS: </small>
                                            <span role="button" data-bs-toggle="popover" data-trigger="focus" class="fw-bolder"
                                                title="APPROVED DETAILS"
                                                data-bs-content="Approved By: {{ $item->staff ? $item->staff->user->name : '-' }} Approved Date: {{ $item->created_at->format('F d,Y') }}">{{ $item->feedback }}</span>
                                        </span>
                                    </div>
                                    <div class="col-md">
                                        <a class="btn btn-outline-info btn-sm rounded-pill btn-form-document mt-2 w-100"
                                            data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                            data-document-url="{{ json_decode($item->file_links)[0] }}"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="View Image">

                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            @break

                            @default
                        @endswitch
                    @else
                        <div class="row">
                            <div class="col-md-8">
                                <p>Missing Document</p>
                            </div>
                            <div class="col-md">
                                <a class="btn btn-outline-warning btn-sm rounded-pill btn-form-document mt-2"
                                    href="{{ route('document-notification') }}?_applicant={{ base64_encode($_account->id) }}"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Send a Notification">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.8325 8.17463L10.109 13.9592L3.59944 9.88767C2.66675 9.30414 2.86077 7.88744 3.91572 7.57893L19.3712 3.05277C20.3373 2.76963 21.2326 3.67283 20.9456 4.642L16.3731 20.0868C16.0598 21.1432 14.6512 21.332 14.0732 20.3953L10.106 13.9602"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif --}}
                </div>
            @endforeach
        </div>
    </main>
@endsection
