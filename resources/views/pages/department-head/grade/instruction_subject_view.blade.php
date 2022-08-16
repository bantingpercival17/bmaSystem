@extends('layouts.app-main')
@php
$_title = 'Grade Submission';
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    @if (request()->input('_academic'))
        <li class="breadcrumb-item">
            <a href="{{ route('department-head.grade-submission') }}">
                <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>{{ $_title }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('department-head.grade-submission') }}?_academic={{ request()->input('_academic') }}">
                {{ Auth::user()->staff->current_academic()->semester . ' | ' . Auth::user()->staff->current_academic()->school_year }}
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ strtoupper($_staff->first_name . ' ' . $_staff->last_name) }}
        </li>
    @else
        <li class="breadcrumb-item">
            <a href="{{ route('department-head.grade-submission') }}">
                <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>{{ $_title }}</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ strtoupper($_staff->first_name . ' ' . $_staff->last_name) }}
        </li>
    @endif

@endsection
@section('js')
    <script>
        $(document).on('click', '.btn-form-grade', function(evt) {
            // $(".form-view").contents().find("body").html("");
            $('.form-view').attr('src', $(this).data('grade-url'))
        });
    </script>
@endsection
@section('page-content')
    <div class="row mt-2">
        <div class="col-md-5">
            <div class="card mb-5">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <img src="{{ asset($_staff->profile_pic($_staff)) }}" class="avatar-130 rounded" alt="#">
                    </div>
                    <div class="ms-5 col-md-8">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $_staff ? strtoupper($_staff->first_name . ' ' . $_staff->last_name) : 'NAME' }}</b>
                            </h4>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-5" data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0"
                data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bolder">Class Handled</h5>
                </div>
                <div class="card-body">
                    @if (count($_staff->subject_handles) > 0)
                        @foreach ($_staff->subject_handles as $_handle)
                            <div class="border-bottom ">
                                <div class="card mb-0 iq-content rounded-bottom">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="mb-sm-0">
                                                <a
                                                    href="{{ route('department-head.grade-submission-view') }}{{ '?_staff=' . request()->input('_staff') }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}{{ '&_subject=' . base64_encode($_handle->id) }}">

                                                    <div class="d-flex">
                                                        <h5 class="">
                                                            {{ $_handle->curriculum_subject->subject->subject_code }}
                                                        </h5>
                                                    </div>
                                                    <p class="mb-0">{{ $_handle->section->section_name }}</p>
                                                </a>
                                            </div>
                                        </div>
                                        <ul class="d-flex mb-0 text-center ">
                                            @if ($_handle->midterm_grade_submission)
                                                @if ($_handle->midterm_grade_submission->is_approved == 1)
                                                    <li class="badge bg-primary me-2">
                                                        <small class="mb-1 fw-bolder">MIDTERM</small>
                                                        <br>
                                                        <small class="mb-1 fw-normal">APPROVED </small>
                                                    </li>
                                                @endif
                                                @if ($_handle->midterm_grade_submission->is_approved === 0)
                                                    <li class="badge bg-danger me-2">
                                                        <small class="mb-1 fw-bolder">MIDTERM</small>
                                                        <br>
                                                        <small class="mb-1 fw-normal">DISAPPROVED</small>
                                                    </li>
                                                @endif
                                                @if ($_handle->midterm_grade_submission->is_approved === null)
                                                    <li class="badge bg-info me-2">
                                                        <small class="mb-1 fw-bolder">MIDTERM</small>
                                                        <br>
                                                        <small class="mb-1 fw-normal">PENDING</small>
                                                    </li>
                                                @endif
                                            @else
                                                <li class="badge bg-secondary me-2">
                                                    <small class="mb-1 fw-bolder">MIDTERM</small>
                                                    <br>
                                                    <small class="mb-1 fw-normal">-</small>
                                                </li>
                                            @endif
                                            @if ($_handle->finals_grade_submission)
                                                @if ($_handle->finals_grade_submission->is_approved == 1)
                                                    <li class="badge bg-primary me-2">
                                                        <small class="mb-1 fw-bolder">FINALS</small>
                                                        <br>
                                                        <small class="mb-1 fw-normal">APPROVED</small>
                                                    </li>
                                                @endif
                                                @if ($_handle->finals_grade_submission->is_approved === 0)
                                                    <li class="badge bg-danger me-2">
                                                        <small class="mb-1 fw-bolder">FINALS</small>
                                                        <br>
                                                        <small class="mb-1 fw-normal">DISAPPROVED</small>
                                                    </li>
                                                @endif
                                                @if ($_handle->finals_grade_submission->is_approved === null)
                                                    <li class="badge bg-info me-2">
                                                        <small class="mb-1 fw-bolder">FINALS</small>
                                                        <br>
                                                        <small class="mb-1 fw-normal">PENDING</small>
                                                    </li>
                                                @endif
                                            @else
                                                <li class="badge bg-secondary me-2">
                                                    <small class="mb-1 fw-bolder">FINALS</small>
                                                    <br>
                                                    <small class="mb-1 fw-normal">-</small>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class=" border-bottom mb-4">
                            <div class="d-flex align-items-center">
                                <div class="mb-3">
                                    <div class="d-flex">
                                        <h5 class="">No Subject</h5>
                                    </div>
                                    <p class="mb-0">No Section</p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-body align-items-center justify-content-between pb-4">
                    <div class="header-title">
                        <div class="d-flex flex-wrap row">
                            <div class="media-support-info mt-2 col-md-7">
                                <h5 class="mb-0">
                                    {{ $_subject_class ? $_subject_class->curriculum_subject->subject->subject_code : 'Subject Name' }}
                                </h5>
                                <p class="mb-0 text-primary">
                                    {{ $_subject_class ? $_subject_class->section->section_name : 'Section Name' }}
                                </p>
                            </div>
                            <div class="col-md">
                                @if ($_subject_class)
                                    <button type="button" class="btn btn-primary btn-sm btn-form-grade w-100 mt-2"
                                        data-bs-toggle="modal" data-bs-target=".grade-view-modal"
                                        data-grade-url="{{ route('department-head.report-view') }}?_subject={{ base64_encode($_subject_class->id) }}&_period={{ request()->input('_period') }}&_preview=pdf&_form=ad2">
                                        View Form AD-02</button>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($_subject_class)
                <div class="card">
                    <div class="card-header align-items-center justify-content-between p-3">
                        <div class="row">
                            <div class="col-md-7">
                                <label for="" class="h5 fw-bolder text-primary"> MIDTERM GRADING SHEET</label>
                            </div>
                            <div class="col-md">
                                <small class="badge bg-primary float-end  btn-form-grade"data-bs-toggle="modal"
                                    data-bs-target=".grade-view-modal"
                                    data-grade-url="{{ route('department-head.report-view') }}?_subject={{ base64_encode($_subject_class->id) }}&_period=midterm&_preview=pdf&_form=ad1">
                                    FORM AD-01
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @foreach ($_subject_class->midterm_grade_remarks as $_remarks)
                            <div class="p-2">
                                @if ($_remarks->is_approved == 1)
                                    <h5 class="text-primary mb-1">APPROVED</h5>
                                @elseif ($_remarks->is_approved === 0)
                                    <h5 class="text-danger mb-1">DISAPPROVED</h5>
                                    <p class="mb-1">{{ $_remarks->comments }}</p>
                                @elseif ($_remarks->is_approved === null)
                                    <h5 class="text-info mb-1">FOR APPROVAL</h5>
                                @endif
                                <div class="d-flex flex-wrap align-items-center mb-1">
                                    <small>Date Submitted: </small>
                                    <small class="ms-2 text-primary">
                                        {{ $_remarks->created_at->format('d F, Y') }}
                                    </small>

                                    @if ($_remarks->is_approved == 1)
                                        <small class="ms-4">Date Verification: </small>
                                        <small class="ms-2 text-primary">
                                            {{ $_remarks->created_at == $_remarks->updated_at ? '-' : $_remarks->updated_at->format('d F, Y') }}
                                        </small>
                                    @endif

                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="card-footer p-2">
                        <hr>
                        <form class="mt-3" action="{{ route('department-head.submission-verification') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="_submission"
                                value="{{ base64_encode($_subject_class->midterm_grade_submission->id) }}">
                            <input type="hidden" name="_status" value="0">
                            <input type="text" class="form-control rounded-pill" placeholder="Leave Remarks"
                                name="_comments">
                            <div class=" d-flex align-items-center mt-2 float-end">
                                <div class="me-4 text-body">
                                    <button class="btn btn-outline-danger rounded-pill btn-xs" type="submit"
                                        value="0" name="_status">DISAPPROVED</button>
                                </div>
                            </div>
                        </form>
                        <form action="{{ route('department-head.submission-verification') }}" method="POST">
                            @csrf
                            <input type="hidden" name="_submission"
                                value="{{ base64_encode($_subject_class->midterm_grade_submission->id) }}">
                            <input type="hidden" name="_status" value="1">
                            <button class="btn btn-outline-primary rounded-pill float-end mt-2 me-3 btn-xs"
                                type="submit">APPROVED</button>
                        </form>
                    </div>
                </div>

                @if (count($_subject_class->finals_grade_remarks) > 0)
                    <div class="card">
                        <div class="card-header align-items-center justify-content-between p-3">
                            <div class="row">
                                <div class="col-md-7">
                                    <label for="" class="h5 fw-bolder text-primary">FINALSS GRADING SHEET</label>
                                </div>
                                <div class="col-md">
                                    <small class="badge bg-primary float-end  btn-form-grade"data-bs-toggle="modal"
                                        data-bs-target=".grade-view-modal"
                                        data-grade-url="{{ route('department-head.report-view') }}?_subject={{ base64_encode($_subject_class->id) }}&_period=finals&_preview=pdf&_form=ad1">
                                        FORM AD-01
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            @foreach ($_subject_class->finals_grade_remarks as $_remarks)
                                <div class="p-2">
                                    @if ($_remarks->is_approved == 1)
                                        <h5 class="text-primary mb-1">APPROVED</h5>
                                    @elseif ($_remarks->is_approved === 0)
                                        <h5 class="text-danger mb-1">DISAPPROVED</h5>
                                        <p class="mb-1">{{ $_remarks->comments }}</p>
                                    @elseif ($_remarks->is_approved === null)
                                        <h5 class="text-info mb-1">FOR APPROVAL</h5>
                                    @endif
                                    <div class="d-flex flex-wrap align-items-center mb-1">
                                        <small>Date Submitted: </small>
                                        <small class="ms-2 text-primary">
                                            {{ $_remarks->created_at->format('d F, Y') }}
                                        </small>

                                        @if ($_remarks->is_approved == 1)
                                            <small class="ms-4">Date Verification: </small>
                                            <small class="ms-2 text-primary">
                                                {{ $_remarks->created_at == $_remarks->updated_at ? '-' : $_remarks->updated_at->format('d F, Y') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div class="card-footer p-2">
                            <hr>
                            <form class="mt-3" action="{{ route('department-head.submission-verification') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="_submission"
                                    value="{{ base64_encode($_subject_class->finals_grade_submission->id) }}">
                                <input type="hidden" name="_status" value="0">
                                <input type="text" class="form-control rounded-pill" placeholder="Leave Remarks"
                                    name="_comments">
                                <div class=" d-flex align-items-center mt-2 float-end">
                                    <div class="me-4 text-body">
                                        <button class="btn btn-outline-danger rounded-pill btn-xs" type="submit"
                                            value="0" name="_status">DISAPPROVED</button>
                                    </div>
                                </div>
                            </form>
                            <form action="{{ route('department-head.submission-verification') }}" method="POST">
                                @csrf
                                <input type="hidden" name="_submission"
                                    value="{{ base64_encode($_subject_class->finals_grade_submission->id) }}">
                                <input type="hidden" name="_status" value="1">
                                <button class="btn btn-outline-primary rounded-pill float-end mt-2 me-3 btn-xs"
                                    type="submit">APPROVED</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif

        </div>

    </div>

    <div class="modal fade grade-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <iframe class="form-view iframe-placeholder" src="" width="100%" height="600px">
                </iframe>
            </div>
        </div>
    </div>
@endsection
