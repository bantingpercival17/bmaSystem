@extends('layouts.app-main')
@php
$_url_role = ['dashboard', 'administrator/applicants', 'accounting/applicants', 'registrar/applicants'];
$_course_enrolled = ['applicant-lists', 'applicant-lists', 'accounting.course-enrolled', 'registrar.course-enrolled'];
$_applicant_view = ['applicant-profile', 'applicant-profile', 'applicant-profile', 'applicant-profile'];
$_course_url = route($_course_enrolled[0]);
$_profile_link = route($_applicant_view[0]);
/* foreach ($_url_role as $key => $_data) {
    $_course_url = request()->is($_data . '*') ? route($_course_enrolled[$key]) : $_course_url;
    $_profile_link = request()->is($_data . '*') ? route($_applicant_view[$key]) : $_profile_link;
} */
$_title = 'Profile View';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item ">
        <a href="/">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Dashboard
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ $_course_url }}?_course={{ base64_encode($_account->course_id) }}">Applicant List</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_title }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-4 col-lg-2">

                        <img src="http://bma.edu.ph/img/student-picture/midship-man.jpg" class="avatar-130 rounded"
                            alt="applicant-profile">
                    </div>
                    <div class="col-md col-lg">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $_account->applicant? strtoupper($_account->applicant->last_name . ', ' . $_account->applicant->first_name): 'APPLICANT NAME' }}</b>
                            </h4>
                            <p class="card-text">
                                <span>
                                    <b>
                                        {{ $_account->applicant ? $_account->applicant_number : 'APPLICANT NO.' }}
                                        |
                                        {{ $_account->applicant ? $_account->course->course_name : 'COURSE' }}
                                    </b>
                                </span>

                            </p>

                        </div>
                    </div>
                </div>
            </div>
            @if ($_account->applicant)
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between  pb-0 p-3">
                        <div class="header-title">
                            <h5 class="mb-1"><b>APPLICANT DETAILS</b></h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills nav-pill mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a href="{{ request()->url() . '?_student=' . request()->input('_student') }}"
                                    class="nav-link {{ request()->input('_fill') ?: 'active' }}">Applicant Profile</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ request()->url() . '?_student=' . request()->input('_student') }}&_fill=document"
                                    class="nav-link {{ request()->input('_fill') != 'document' ? '' : 'active' }}">Document
                                    Cheking</a>
                            </li>
                        </ul>
                        @if (!request()->input('_fill'))
                            <div class="tab-content" id="pills-tabContent-2">
                                <div class="tab-pane fade active show">
                                    <div class="form-view">
                                        <h6 class="mb-1"><b>FULL NAME</b></h6>
                                        <div class="row">
                                            <div class="col-xl col-md-6 ">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">Last
                                                        name</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->last_name) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl col-md-6 ">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">First
                                                        name</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->first_name) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl col-md-6 ">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">Middle
                                                        name</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->middle_name) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-6 ">
                                                <div class="form-group">
                                                    <small for="example-text-input"
                                                        class="form-control-label">Extension</small>
                                                    <span
                                                        class="form-control">{{ $_account->applicant->extention_name ? ucwords($_account->applicant->extention_name) : 'none' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input"
                                                        class="form-control-label">Gender</small>
                                                    <span class="form-control">{{ ucwords('male') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input"
                                                        class="form-control-label">Birthday</small>
                                                    <span
                                                        class="form-control">{{ $_account->applicant->birthday }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">Birth
                                                        Place</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->birth_place) }}</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-xl col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">Civil
                                                        Status</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->civil_status) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input"
                                                        class="form-control-label">Nationality</small>
                                                    <span
                                                        class="form-control">{{ $_account->applicant->nationality }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="mb-1"><b>ADDRESS</b></h6>
                                        <div class="row">
                                            <div class="col-xl-5 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">Hous no /
                                                        Street / Bldg
                                                        no</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->street) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input"
                                                        class="form-control-label">Barangay</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->barangay) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">Zip
                                                        Code</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->zip_code) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input"
                                                        class="form-control-label">Municipality</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->municipality) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input"
                                                        class="form-control-label">Province</small>
                                                    <span
                                                        class="form-control">{{ ucwords($_account->applicant->province) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="mb-1"><b>CONTACT DETIALS</b></h6>
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">Contact
                                                        Number</small>

                                                    <span
                                                        class="form-control">{{ $_account->contact_number ?: '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">Email</small>
                                                    <span class="form-control">{{ $_account->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="mb-1"><b>EDUCATIONA BACKGROUD</b></h6>
                                        <label for="" class="form-label fw-bolder">ELEMENTARY</label>
                                        <div class="row">
                                            <div class="col-xl-8 col-md mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">SCHOOL
                                                        NAME</small>

                                                    <span
                                                        class="form-control">{{ $_account->applicant->elementary_school_name ?: '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">YEAR
                                                        GRADUATED</small>

                                                    <span
                                                        class="form-control">{{ $_account->applicant->elementary_school_year ?: '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 col-md mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">SCHOOL
                                                        ADDRESS</small>
                                                    <span
                                                        class="form-control">{{ $_account->applicant->elementary_school_address }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="" class="form-label fw-bolder">JUNIOR HIGH SCHOOL</label>
                                        <div class="row">
                                            <div class="col-xl-8 col-md mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">SCHOOL
                                                        NAME</small>

                                                    <span
                                                        class="form-control">{{ $_account->applicant->junior_high_school_name ?: '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">YEAR
                                                        GRADUATED</small>

                                                    <span
                                                        class="form-control">{{ $_account->applicant->junior_high_school_year ?: '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 col-md mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">SCHOOL
                                                        ADDRESS</small>
                                                    <span
                                                        class="form-control">{{ $_account->applicant->junior_high_school_address }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($_account->applicant->senior_high_school_name)
                                        <label for="" class="form-label fw-bolder">SENIOR HIGH SCHOOL</label>
                                        <div class="row">
                                            <div class="col-xl-8 col-md mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">SCHOOL
                                                        NAME</small>

                                                    <span
                                                        class="form-control">{{ $_account->applicant->senior_high_school_name ?: '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">YEAR
                                                        GRADUATED</small>

                                                    <span
                                                        class="form-control">{{ $_account->applicant->senior_high_school_year ?: '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 col-md mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="form-control-label">SCHOOL
                                                        ADDRESS</small>
                                                    <span
                                                        class="form-control">{{ $_account->applicant->senior_high_school_address }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if (request()->input('_fill') == 'document')
                            <div class="tab-content" id="pills-tabContent-2">
                                <div class="tab-pane fade active show">
                                    @if (count($_account->applicant_documents) > 0)
                                        @foreach ($_account->applicant_documents as $item)
                                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex align-items-center message-icon me-3">
                                                        <span
                                                            class="ms-1 fw-bolder">{{ $item->document->document_name }}</span>
                                                    </div>
                                                    <div class="">
                                                        <a class="badge bg-primary btn-form-document col"
                                                            data-bs-toggle="modal" data-bs-target=".document-view-modal"
                                                            data-document-url="{{ json_decode($item->file_links)[0] }}">
                                                            view
                                                        </a>
                                                    </div>
                                                </div>
                                                @if ($_account->document_history($item->document_id)->count() > 0)
                                                    <div class="history">
                                                        <a tabindex="0" class="badge bg-secondary text-white" role="button"
                                                            data-bs-toggle="popover" data-trigger="focus"
                                                            title="List of Dissapproved Requirments"
                                                            data-bs-content="We have {{ $_account->document_history($item->document_id)->count() }} disapproved Document/s">Document
                                                            History</a>
                                                    </div>
                                                @endif

                                            </div>
                                            @if ($item->is_approved === 1)
                                                <span class="fw-bolder text-primary">DOCUMENT APPROVED</span>
                                                <div class="row">
                                                    <div class="col-md"><small><i>VERIFIED
                                                                BY:</i></small>
                                                        <b>{{ $item->staff ? $item->staff->user->name : '-' }}</b>
                                                    </div>
                                                    <div class="col-md"><small><i>VERIFIED DATE:</i></small>
                                                        {{ $item->created_at->format('F d,Y') }}</div>
                                                </div>
                                                @endif @if ($item->is_approved === 2)
                                                    <span class="fw-bolder text-danger">DOCUMENT DISAPPROVED</span><br>
                                                    <span class="text-muted"><i>Remarks: </i>
                                                        <b> {{ $item->feedback }}</b></span>
                                                    <div class="row">
                                                        <div class="col-md"><small><i>VERIFIED
                                                                    BY:</i></small>
                                                            <b>{{ $item->staff ? $item->staff->user->name : '-' }}</b>
                                                        </div>
                                                        <div class="col-md"><small><i>VERIFIED DATE:</i></small>
                                                            {{ $item->created_at->format('F d,Y') }}</div>
                                                    </div>
                                                @endif
                                                @if ($item->is_approved === null)
                                                    <form class="comment-text d-flex align-items-center mt-3"
                                                        action="{{ route('document-verification') }}">
                                                        <input type="hidden" name="_document"
                                                            value="{{ base64_encode($item->id) }}">
                                                        <input type="hidden" name="_verification_status" value="0">
                                                        <input type="text" class="form-control rounded-pill"
                                                            name="_comment" placeholder="Comment!">
                                                        <div class="comment-attagement d-flex">
                                                            <button type="submit"
                                                                class=" me-2 btn btn-danger btn-sm rounded-pill">DISAPPROVE
                                                            </button>
                                                            <a href="{{ route('document-verification') }}?_document={{ base64_encode($item->id) }}&_verification_status=1"
                                                                class="me btn btn-primary btn-sm rounded-pill">
                                                                APPROVE
                                                            </a>
                                                        </div>
                                                    </form>
                                                @endif

                                                <hr>
                                            @endforeach
                                        @else
                                            <div class="mt-5">
                                                No Attach Requirement. <a
                                                    href="{{ route('document-notification') }}?_applicant={{ base64_encode($_account->id) }}"
                                                    class="badge bg-info">click here</a> to
                                                notify the applicant.
                                                @foreach ($_account->empty_documents() as $item)
                                                    <div
                                                        class="d-flex flex-wrap justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex align-items-center message-icon me-3">
                                                                <span
                                                                    class="ms-1 fw-bolder">{{ $item->document_name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                @endforeach
                                            </div>
                                        @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <span class="fw-bolder">
                        {{ strtoupper($_account->course->course_name) }}
                    </span>

                </div>
                <div>
                    <div class="d-flex justify-content-between">
                        No. Result:
                        <span class="text-muted fw-bolder"> {{ count($_applicants) }}</span>
                    </div>
                </div>
            </div>
            <div class="list-view">
                @if (count($_applicants) > 0)
                    @foreach ($_applicants as $item)
                        <div class="card mb-2">
                            <a href="{{ $_profile_link }}?_student={{ base64_encode($item->id) }}">
                                <div class="row no-gutters">
                                    <div class="col-md col-lg">
                                        <div class="card-body">
                                            <span class="card-title text-primary h5">
                                                <b>{{ $item->applicant? strtoupper($item->applicant->last_name . ', ' . $item->applicant->first_name): 'APPLICANT NAME' }}</b>
                                            </span> <br>
                                            <small class="fw-bolder">
                                                {{ $item->applicant ? $item->applicant_number : 'APPLICANT NO.' }} |
                                                {{ $item->applicant ? $item->course->course_name : 'COURSE' }}
                                            </small>

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="card mb-2">
                        <a href="{{ $_profile_link }}?_student={{ base64_encode($item->id) }}">
                            <div class="row no-gutters">
                                <div class="col-md col-lg">
                                    <div class="card-body">
                                        <span class="card-title text-primary h5">
                                            <b>No Data Result</b>
                                        </span> <br>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
    <div class="modal fade document-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Document Review</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="btn-group " role="group" aria-label="Basic example">

                    </div>
                    <iframe class="iframe-container form-view iframe-placeholder" width="100%" height="600px">
                    </iframe>
                </div>
            </div>
        </div>
    @endsection
