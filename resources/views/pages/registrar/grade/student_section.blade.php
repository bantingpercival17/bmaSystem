@extends('layouts.app-main')
@php
    $_title = 'Semestral Grades';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a
            href="{{ route('registrar.semestral-grades') }}{{ request()->input('_academic') ? '?_academic=' . request()->input('_academic') : '' }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </a>

    </li>
    {{--  <li class="breadcrumb-item ">
        <a href="">
            {{ $_section->course->course_name }}
        </a>

    </li> --}}
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_section->section_name }}
    </li>

@endsection
@section('page-content')
    <div class=" mt-6 py-0">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">{{ $_section->section_name }}</h4>
                    <h6 class="card-title">Semestral Grade</h6>
                </div>
                <div class="card-tool">
                    <a href="{{ route('registrar.semestral-grade-publish-all') }}?section={{ request()->input('_section') }}&academic={{ request()->input('_academic') }}"
                        class="btn btn-primary btn-sm">PUBLISH ALL GRADES</a>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs justify-content-center nav-fill" id="myTab-2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " id="student-tab-justify" data-bs-toggle="tab" href="#student-justify"
                            role="tab" aria-controls="student" aria-selected="true">STUDENT LIST</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" id="subject-tab-justify" data-bs-toggle="tab" href="#subject-justify"
                            role="tab" aria-controls="subject" aria-selected="false">SUBJECT LIST</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent-3">
                    <div class="tab-pane fade" id="student-justify" role="tabpanel" aria-labelledby="student-tab-justify">
                        <<div class="table-responsive">
                            <table class="table table-striped" id="datatable" data-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Student Number</th>
                                        <th>Midshipman Name</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($_section->student_sections) > 0)
                                        @foreach ($_section->student_sections as $_key => $_data)
                                            <tr>
                                                <td>{{ $_data->student->account ? $_data->student->account->student_number : '-' }}
                                                </td>
                                                <td>{{ strtoupper($_data->student->last_name . ', ' . $_data->student->first_name) }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('registrar.semestral-grade-form-ad2') }}?student={{ base64_encode($_data->student->id) }}&_section={{ request()->input('_section') }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}"
                                                        class="btn btn-sm btn-primary" target="_blank">FORM AD-02-A</a>
                                                    @if ($_data->student->grade_publish)
                                                        <span class="badge bg-secondary">GRADE PUBLISHED <br>
                                                            {{ $_data->student->grade_publish->staff->user->name . ' - ' . $_data->student->grade_publish->created_at->format('F d, Y') }}</span>
                                                    @else
                                                        <a href="{{ route('registrar.semestral-grade-publish') }}? student={{ base64_encode($_data->student->id) }}&_academic={{ request()->input('_academic') }}"
                                                            class="btn btn-sm btn-info text-white">PUBLISH GRADE</a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th colspan="3">No Data</th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                    </div>
                </div>
                <div class="tab-pane fade show active" id="subject-justify" role="tabpanel"
                    aria-labelledby="subject-tab-justify">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>SUBJECT NAME / TEACHER NAME</th>
                                    <th>GRADE STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($_section->subject_class) > 0)
                                    @foreach ($_section->subject_class as $item)
                                        <tr>
                                            <td>
                                                <span
                                                    class="text-primary fw-bolder">{{ $item->curriculum_subject->subject->subject_name }}</span>
                                                <br>
                                                <small class="fw-bolder text-muted">
                                                    {{ strtoupper($item->staff->first_name . ' ' . $item->staff->last_name) }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="ms-2">
                                                        @if ($item->finals_grade_submission && $item->midterm_grade_submission)
                                                            @if ($item->finals_grade_submission->is_approved === 1 && $item->midterm_grade_submission->is_approved === 1)
                                                                @if ($item->grade_final_verification)
                                                                    <span class="badge bg-primary">Grade
                                                                        Verified</span> <br>
                                                                    <button type="button"
                                                                        class="btn btn-outline-primary btn-sm btn-form-grade  mt-2"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target=".grade-view-modal"
                                                                        data-grade-url="{{ route('registrar.subject-grade') }}?_subject={{ base64_encode($item->id) }}&_period=finals&_preview=pdf&_form=ad2">
                                                                        VIEW FORM AD-02</button>
                                                                @else
                                                                    <span class="badge bg-info">FOR
                                                                        APPROVAL <br> OF DEAN</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <span class="badge bg-info">FOR
                                                                APPROVAL OF <br> DEPARTMENT HEAD</span>
                                                        @endif

                                                    </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th class="4">No Subject Class</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade grade-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <iframe class="form-view iframe-placeholder" src="">
                </iframe>
            </div>
        </div>
    </div>
@section('css')
    <style>
        .form-view {
            height: 100vh;
        }
    </style>
@endsection
@section('js')
    <script>
        $(document).on('click', '.btn-form-grade', function(evt) {
            // $(".form-view").contents().find("body").html("");
            $('.form-view').attr('src', '')
            $('.form-view').attr('src', $(this).data('grade-url'))
        });
    </script>
@endsection
@endsection
