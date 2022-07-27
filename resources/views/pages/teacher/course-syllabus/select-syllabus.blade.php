@extends('layouts.app-main')
@php
$_title = $_subject->section->section_name . ' | ' . $_subject->curriculum_subject->subject->subject_code;
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('teacher.subject-list') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Subject</a>
    </li>
    <li class="breadcrumb-item">
        <a
            href="{{ route('teacher.subject-list') }}?_academic={{ base64_encode($_subject->academic_id) }}">{{ $_subject->academic->school_year . ' - ' . $_subject->academic->semester }}</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $_title }}</li>
@endsection
@section('page-content')

    <div class="conatiner-fluid content-inner mt-6 py-0">

        @if (count($_course_syllabus) > 0)
            <div class="row">
                @foreach ($_course_syllabus as $item)
                    <div class="col-md">
                        <div class="card">
                            <div class="card-body">
                                <div class="float-end">
                                    <button class="btn btn-primary btn-sm btn-add"
                                        data-route="{{ route('teacher.course-syllabus-add') . '?course_syllabus=' . base64_encode($item->id) . '&_subject=' . request()->input('_subject') }}">ADD</button>
                                </div>
                                <span class="h3 text-primary fw-bolder">
                                    {{ $item->subject->subject_code }}
                                </span>
                                <div class="row">
                                    <div class="col-md">
                                        <small>CREATED BY</small><br>
                                        <label for=""
                                            class="fw-bolder">{{ $item->staff->first_name . ' ' . $item->staff->last_name }}</label>
                                    </div>
                                </div>


                            </div>
                        </div>
                @endforeach

            </div>
        @else
            <div class="row">

                <div class="col-md">
                    <a>
                        <div class="card">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md">
                                        <span class="h3 text-primary fw-bolder">
                                            NO COURSE SYLLBUS
                                        </span>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endif



    </div>

@endsection

@section('js')
    <script>
        $('.btn-add').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var _url = $(this).data('route');
                if (result.isConfirmed) {
                    window.location.href = _url
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
