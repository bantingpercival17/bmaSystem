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

                        {{-- <img src="http://bma.edu.ph/img/student-picture/midship-man.jpg" class="avatar-130 rounded"
                            alt="applicant-profile"> --}}
                            @if ($_account->image)
                            <img src="{{json_decode($_account->image->file_links)[0]}}" class="avatar-130 rounded"
                            alt="applicant-profile">
                            @else
                                
                            @endif
                           
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
                        <div>
                            <a href="" class="btn btn-sm btn-info mb-3 text-white">OVERVIEW</a>
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
                            @if ($_account->applicant_examination)
                                @if ($_account->applicant_examination->is_finish == 1)
                                    <li class="nav-item">
                                        <a href="{{ request()->url() . '?_student=' . request()->input('_student') }}&_fill=entrance-examination"
                                            class="nav-link {{ request()->input('_fill') != 'entrance-examination' ? '' : 'active' }}">Entrance
                                            Examination</a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                      @include('pages.general-view.applicants.profile_tab')
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
