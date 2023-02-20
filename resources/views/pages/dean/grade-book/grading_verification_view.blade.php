@extends('layouts.app-main')
@php
    $_title = 'Grade Verification';
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    @if (request()->input('_academic'))
        <li class="breadcrumb-item ">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <a
                href="{{ route('dean.grade-verification-view') }}?_academic={{ base64_encode(Auth::user()->staff->current_academic()->id) }}">
                {{ Auth::user()->staff->current_academic()->semester . ' | ' . Auth::user()->staff->current_academic()->school_year }}
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ $_section->section_name }}
        </li>
    @else
        <li class="breadcrumb-item ">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ $_section->section_name }}
        </li>
    @endif


@endsection
@section('page-content')

    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">{{ $_section->section_name }}
                    </h4>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SUBJECT NAME / TEACHER NAME</th>
                                <th>FORM </th>
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
                                        <td class="d-flex justify-content-between">
                                            <div class="">
                                                @if ($item->midterm_grade_submission)
                                                    @if ($item->midterm_grade_submission->is_approved === 1)
                                                        <button type="button"
                                                            class="btn btn-primary btn-sm btn-form-grade w-100 mt-2"
                                                            data-bs-toggle="modal" data-bs-target=".grade-view-modal"
                                                            data-grade-url="{{ route('dean.grading-sheet-view') }}?_subject={{ base64_encode($item->id) }}&_period=midterm&_preview=pdf&_form=ad1">
                                                            MIDTERM</button>
                                                    @else
                                                        <span class="badge bg-secondary fw-bolder">
                                                            MIDTERM GRADE <br> ONGOING CHECKING
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary fw-bolder">
                                                        MIDTERM GRADE <br> NOT YET SUBMMITED
                                                    </span>
                                                @endif
                                                <br>
                                                @if ($item->finals_grade_submission)
                                                    @if ($item->finals_grade_submission->is_approved === 1)
                                                        <button type="button"
                                                            class="btn btn-primary btn-sm btn-form-grade w-100 mt-2"
                                                            data-bs-toggle="modal" data-bs-target=".grade-view-modal"
                                                            data-grade-url="{{ route('dean.grading-sheet-view') }}?_subject={{ base64_encode($item->id) }}&_period=midterm&_preview=pdf&_form=ad1">
                                                            FINALS</button>
                                                    @else
                                                        <span class="badge bg-secondary fw-bolder mt-2">
                                                            FINALS GRADE <br> ONGOING CHECKING
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary fw-bolder mt-2">
                                                        FINALS GRADE <br> NOT YET SUBMMITED
                                                    </span>
                                                @endif
                                                <br>
                                                @if ($item->finals_grade_submission || $item->midterm_grade_submission)
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm btn-form-grade w-100 mt-2"
                                                        data-bs-toggle="modal" data-bs-target=".grade-view-modal"
                                                        data-grade-url="{{ route('dean.grading-sheet-view') }}?_subject={{ base64_encode($item->id) }}&_period=finals&_preview=pdf&_form=ad2">
                                                        FORM AD-02</button>
                                                @endif
                                            </div>
                                            <div class="ms-2">
                                                @if ($item->finals_grade_submission && $item->midterm_grade_submission)
                                                    @if ($item->finals_grade_submission->is_approved === 1 && $item->midterm_grade_submission->is_approved === 1)
                                                        @if ($item->grade_final_verification)
                                                            <span class="badge bg-primary float-start">Grade Verified</span>
                                                            @if (count($item->grade_publish()) <= 0)
                                                                <a href="{{ route('dean.grade-publish') }}?subject_class='{{ base64_encode($item->id) }}"
                                                                    class="btn btn-info btn-sm text-white w-100  mt-2">PUBLISH</a>
                                                            @endif
                                                        @else
                                                            <form action="{{ route('dean.grade-verification') }}"
                                                                method="get">
                                                                <div class="me-2">
                                                                    <textarea name="_remarks" class="form-control mt-2" cols="20" rows="2"></textarea>
                                                                </div>
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md">
                                                                        <a href="{{ route('dean.grade-verification') }}?subject_class='{{ base64_encode($item->id) }}'&_subject={{ base64_encode($item->id) }}&_status=1&_period=finals"
                                                                            class="btn btn-info btn-sm text-white w-100  mt-2">APPROVED</a>
                                                                    </div>
                                                                    <div class="col-md">
                                                                        <input type="hidden" name="_status" value="0">
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-sm w-100 mt-2">DISAPPROVED</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    @endif
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
    <div class="modal fade grade-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <iframe class="form-view iframe-placeholder" src="" width="100%" height="600px">
                </iframe>
            </div>
        </div>
    </div>
@section('js')
    <script>
        $(document).on('click', '.btn-form-grade', function(evt) {
            // $(".form-view").contents().find("body").html("");
            $('.form-view').attr('src', $(this).data('grade-url'))
        });
    </script>
@endsection
@endsection
