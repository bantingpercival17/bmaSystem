@extends('layouts.app-main')
@php
$_title = 'Create Course Syllabus';
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('teacher.course-syllabus') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Course Syllabus</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $_title }}</li>
@endsection
@section('page-content')
    <div class="content">
        <div class="card">
            <div class="card-header">
                <label for="" class="fw-bolder text-primary h4">SET-UP COURSE SYLLABUS</label>
            </div>
            <div class="card-body">
                @if (request()->input('_subject'))
                    <form action="{{ route('teacher.course-syllabus-store') }}" method="post" id="form-course-syllabus">
                        @csrf
                        <input type="hidden" name="subject" value="{{ $_subject->id }}">
                        <div class="row">
                            <div class="col-md-3">
                                <small for="" class="form-label">COURSE CODE</small>
                                <label for="" class="form-control fw-bolder">{{ $_subject->subject_code }}</label>
                            </div>
                            <div class="col-md">
                                <small for="" class="form-label">COURSE DESCRIPTIVE TITLE</small>
                                <label for="" class="form-control fw-bolder">{{ $_subject->subject_name }}</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <small for="" class="form-label">NAME OF PROGRAM</small>
                                <select name="course"class="form-select">
                                    <option value="1">MARINE ENGINEERING</option>
                                    <option value="2">MARINE TRANSPORTATION</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <small for="" class="form-label">COURSE CREDITS</small>
                                <label for="" class="form-control fw-bolder">
                                    {{ $_subject->units . ($_subject->units > 1 ? ' units' : ' unit') }}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                           
                            <div class="col-md">
                                <small for="" class="form-label">LECTURE HOURS PER WEEK</small>
                                <label for=""
                                    class="form-control fw-bolder">{{ $_subject->lecture_hours . ($_subject->lecture_hours > 1 ? ' hours' : ' hour') }}</label>
                            </div>
                            <div class="col-md">
                                <small for="" class="form-label">LABORATORY HOURS PER WEEK</small>
                                <label for=""
                                    class="form-control fw-bolder">{{ $_subject->laboratory_hours . ($_subject->laboratory_hours > 1 ? ' hours' : ' hour') }}</label>
                            </div>

                        </div>
                        <div class="form-group">
                            <small for="" class="form-label">COURSE DESCRIPTION <span
                                    class="text-danger">*</span></small>
                            <textarea name="course_description" class="form-control" cols="30" rows="5" required></textarea>
                            @error('course_description')
                                <label for="" class="badge bg-danger text-small mt-2">{{ $message }}</label>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md">
                                <div class="form-group">
                                    <small for="" class="form-label">PREREQUISITE <span
                                            class="text-danger">*</span></small>
                                    <select name="prerequisite" id="" class="form-select">
                                        <option value="none">NONE</option>
                                        @foreach ($_subjects as $subject)
                                            <option value="{{ $subject->subject_code }}">{{ $subject->subject_code }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('prerequisite')
                                        <label for=""
                                            class="badge bg-danger text-small mt-2">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <small for="" class="form-label">CO-REQUISITE <span
                                            class="text-danger">*</span></small>
                                    <select name="co_requisite" id="" class="form-select">
                                        <option value="none">NONE</option>
                                        @foreach ($_subjects as $subject)
                                            <option value="{{ $subject->subject_code }}">
                                                {{ $subject->subject_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('co_requisite')
                                        <label for=""
                                            class="badge bg-danger text-small mt-2">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <small for="" class="form-label">SEMESTER OFFERED <span
                                            class="text-danger">*</span></small>
                                    <select name="semester" class="form-select">
                                        <option value="1st semester">1st Semester</option>
                                        <option value="2nd semester">2nd Semester</option>
                                    </select>
                                    @error('semester')
                                        <label for=""
                                            class="badge bg-danger text-small mt-2">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary form-submit-button float-end"
                            data-form="form-course-syllabus">CREATE
                            NOW</button>
                        {{-- <div class="form-group">
                            <small for="" class="form-label">PROGRAM OUTCOMES
                                <span class="text-danger">*</span></small>
                            <input type="text" class="form-control">
                            @error('program_educational_objective')
                                <label for="" class="badge bg-danger text-small mt-2">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <small for="" class="form-label">COMPETENCE/S
                                <span class="text-danger">*</span></small>
                            <textarea id="txtEditor-1" class="form-control txtEditor"></textarea>
                            @error('program_educational_objective')
                                <label for="" class="badge bg-danger text-small mt-2">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <small for="" class="form-label">KUP
                                <span class="text-danger">*</span></small>
                            <textarea id="txtEditor-2" class="form-control txtEditor"></textarea>
                            @error('program_educational_objective')
                                <label for="" class="badge bg-danger text-small mt-2">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <small for="" class="form-label">COURSE OUTCOME
                                <span class="text-danger">*</span></small>
                            <textarea id="txtEditor-2" class="form-control txtEditor"></textarea>
                            @error('program_educational_objective')
                                <label for="" class="badge bg-danger text-small mt-2">{{ $message }}</label>
                            @enderror
                        </div> --}}
                    </form>
                @else
                    <label for="" class="fw-bolder h4 text-primary">SELECT SUBJECT</label>
                    <table table id="datatable" class="table table-striped" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>COURSE CODE</th>
                                <th>COURSE DESCRIPIVE TITLE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($_subjects) > 0)
                                @foreach ($_subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->subject_name }}</td>
                                        <td>
                                            <a href="{{ route('teacher.course-syllabus-create') . '?_subject=' . base64_encode($subject->id) }}"
                                                class="btn btn-primary btn-sm">CREATE</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">NO DATA</td>
                                </tr>
                            @endif

                        </tbody>

                    </table>
                @endif

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.form-submit-button').click(function(event) {
            Swal.fire({
                title: 'Course Syllabus',
                text: "do you want to submit?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {
                    console.log(form)
                    document.getElementById(form).submit()
                    //$('#' + form).submit();
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
