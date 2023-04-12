@extends('layouts.app-main')
@php
    $_title = 'Midshipman';
@endphp
@section('page-title', 'Midshipman')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>{{ $_title }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        {{-- <img src="{{ $midshipman ? $midshipman->profile_pic($midshipman->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="#"> --}}
                    </div>
                    <div class="col-md ps-0">
                        <div class="card-body p-3 me-2">
                            <label for=""
                                class="fw-bolder text-primary h4">{{ $midshipman ? strtoupper($midshipman->last_name . ', ' . $midshipman->first_name) : 'MIDSHIPMAN NAME' }}</label>
                            <p class="mb-0">
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $midshipman ? ($midshipman->account ? $midshipman->account->student_number : 'NEW STUDENT') : 'STUDENT NUMBER' }}
                                </small> -
                                <small class="fw-bolder badge bg-secondary">
                                    {{ $midshipman ? $midshipman->enrollment_assessment->course->course_name : 'COURSE' }}
                                </small>
                            </p>
                            <div class="row mt-0">
                                <div class="col-md">
                                    <small class="fw-bolder text-muted">CURRICULUM:</small> <br>
                                    <small
                                        class="badge bg-primary">{{ $midshipman ? ($midshipman->enrollemnt_assessment ? strtoupper($midshipman->enrollemnt_assessment->curriculum->curriculum_name) : 'CURRICULUM') : 'CURRICULUM' }}
                                    </small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @if ($midshipman)
                <div class="card ">
                    <div class="card-header p-4">
                        <p class="card-title text-primary h4"><b>SHIPBOARD APPLICATION</b></p>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="form-group col-md">
                                    <label class="form-label-sm"><small>COMPANY NAME</small></label>
                                    <br>
                                    <label
                                        class="text-primary"><b>{{ $midshipman->shipboard_training->company_name }}</b></label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-label-sm"><small>OBT BATCH</small></label>
                                    <br>
                                    <label
                                        class=" text-primary"><b>{{ $midshipman->shipboard_training->sbt_batch }}</b></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md">
                                    <label class="form-label-sm"><small>VESSEL NAME</small></label>
                                    <br>
                                    <label class="text-primary">
                                        <b>{{ $midshipman->shipboard_training->vessel_name }}</b>
                                    </label>
                                </div>
                                <div class="form-group col-md">
                                    <label class="form-label-sm"><small>VESSEL TYPE</small></label>
                                    <br>
                                    <label
                                        class="text-primary"><b>{{ $midshipman->shipboard_training->vessel_type }}</b></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md">
                                    <label class="form-label-sm"><small>SEA EXPERIENCE</small></label>
                                    <br>
                                    <label
                                        class="text-primary"><b>{{ strtoupper($midshipman->shipboard_training->shipping_company) }}</b></label>
                                </div>
                                <div class="form-group col-md">
                                    <label class="form-label-sm"><small>STATUS</small></label>
                                    <br>
                                    <label class="text-primary">
                                        <b> {{ strtoupper($midshipman->shipboard_training->shipboard_status) }}
                                        </b>
                                    </label>
                                </div>
                                <div class="form-group col-md">
                                    <label class="form-label-sm"><small>DATE OF EMBARKED</small></label>
                                    <br>
                                    <label
                                        class="text-primary"><b>{{ $midshipman->shipboard_training->embarked }}</b></label>
                                </div>
                            </div>
                            {{--  <a href="{{ route('onboard.midshipman') }}?_midshipman={{ request()->input('_midshipman') }}&edit=true"
                            class="btn btn-info text-white w-100">UPDATE DETAILS</a> --}}
                        </form>
                        <label for="" class="form-label h5 text-primary fw-bolder mt-3">DOCUMENT REQUIREMENTS</label>
                        <div class="document-requirements">
                            @foreach ($midshipman->shipboard_training->document_requirements as $requirement)
                                <div class="form-group">

                                    <span
                                        class="fw-bolder text-secondary">{{ strtoupper($requirement->documents->document_name) }}</span>
                                    <small class="badge bg-primary btn-form-document float-left" data-bs-toggle="modal"
                                        data-bs-target=".document-view-modal"
                                        data-document-url="{{ $requirement->file_path }}" title=""
                                        data-bs-original-title="View Image">
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
                                    </small>
                                    @if ($requirement->document_status == 1)
                                        <br>
                                        <small class="fw-bolder text-primary">DOCUMENT APPROVED</small>
                                        <br>
                                        <span>
                                            <small>APPROVED DATE:</small>
                                            <small role="button" data-bs-toggle="popover" data-trigger="focus"
                                                class="fw-bolder" title="APPROVED DETAILS"
                                                data-bs-content="Approved By: {{ $requirement->staff ? $requirement->staff->user->name : '-' }} Approved Date: {{ $requirement->updated_at->format('F d,Y') }}">{{ $requirement->updated_at->format('F d,Y') }}</small>
                                        </span>
                                    @elseif($requirement->document_status == 2)
                                        <br>
                                        <small class="fw-bolder text-danger">DOCUMENT DISAPPROVED</small>
                                        <br>
                                        <span>
                                            <small>REMARKS: </small>
                                            <span role="button" data-bs-toggle="popover" data-trigger="focus"
                                                class="fw-bolder" title="APPROVED DETAILS"
                                                data-bs-content="Approved By: {{ $requirement->staff ? $requirement->staff->user->name : '-' }} Verified Date: {{ $requirement->updated_at->format('F d,Y') }}">{{ $requirement->document_comment }}</span>
                                        </span>
                                    @else
                                        <div class="form-group">
                                            <form class="row"
                                                action="{{ route('onboard.midshipman-shipboard-application') }}">
                                                <div class="col-md-9">
                                                    <input type="hidden" name="_document"
                                                        value="{{ base64_encode($requirement->id) }}">
                                                    <input type="text"
                                                        class="form-control form-control-sm rounded-pill mt-2"
                                                        name="_comment" placeholder="Comment!" required="">
                                                </div>
                                                <div class="col-md">
                                                    <a href="{{ route('onboard.midshipman-shipboard-application') . '?_document=' . base64_encode($requirement->id) . '&document_status=1' }}"
                                                        class="mt-2 btn btn-outline-primary btn-sm rounded-pill "
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="Approved Document">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M8.43994 12.0002L10.8139 14.3732L15.5599 9.6272"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </a>
                                                    <button type="submit"
                                                        class=" mt-2 btn btn-outline-danger btn-sm rounded-pill "
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="Disapprove Document">
                                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M14.3955 9.59497L9.60352 14.387"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                            </path>
                                                            <path d="M14.3971 14.3898L9.60107 9.59277"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                            </path>
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M16.3345 2.75024H7.66549C4.64449 2.75024 2.75049 4.88924 2.75049 7.91624V16.0842C2.75049 19.1112 4.63549 21.2502 7.66549 21.2502H16.3335C19.3645 21.2502 21.2505 19.1112 21.2505 16.0842V7.91624C21.2505 4.88924 19.3645 2.75024 16.3345 2.75024Z"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </button>

                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>


                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-4">
            <form action="" method="get">
                <div class="form-group search-input">
                    <input type="search" class="form-control" placeholder="Search..." name="students">
                </div>
            </form>
            <div class=" d-flex justify-content-between mb-2">
                <h6 class=" fw-bolder text-info">
                    {{ request()->input('student') ? 'Search Result: ' . request()->input('student') : 'Recent Midshipman' }}
                </h6>
                <span class="text-primary h6">
                    No. Result: <b>{{ count($students) }}</b>
                </span>

            </div>
            @if ($students)
                @foreach ($students as $item)
                    <div class="card border-bottom border-4 border-0 text-primary ">
                        <a
                            href="{{ route('onboard.midshipman') }}?midshipman={{ base64_encode($item->id) }}{{ request()->input('_course') ? '&_course=' . request()->input('_course') : '' }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="text-primary"><b>{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</b></span>
                                    </div>
                                    <div>
                                        @if ($item->account)
                                            <span class="text-primary">{{ $item->account->student_number }}</span>
                                        @else
                                            <small class="badge bg-primary">NEW STUDENT</small>
                                        @endif

                                    </div>
                                </div>
                                <span>
                                    <small
                                        class="badge {{ $item->enrollment_assessment->color_course() }}">{{ $item->enrollment_assessment ? $item->enrollment_assessment->course->course_name : '-' }}</small>

                                </span>
                            </div>
                        </a>

                    </div>
                @endforeach
            @else
                <div class="card border-bottom border-4 border-0 border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>NO DATA</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
                        <button type="button" class="btn btn-primary btn-sm" onclick="rotateImg(Math.PI/2)">Rotate
                            Left</button>
                        {{-- <button type="button" class="btn btn-primary btn-sm" onclick="initDraw()">Reset</button> --}}
                        <button type="button" class="btn btn-primary btn-sm" onclick="view.rotate(Math.PI/2)">Rotate
                            Right</button>
                    </div>
                </div>
                <iframe class="iframe-container form-view iframe-placeholder" width="100%" height="600px">
                </iframe>
            </div>
        </div>
    </div>
@endsection
