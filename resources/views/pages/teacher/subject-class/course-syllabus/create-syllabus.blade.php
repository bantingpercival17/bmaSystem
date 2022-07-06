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
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $_subject->id }}">
                    <div class="row">
                        <div class="col-md-3">
                            <small for="" class="form-label">COURSE CODE</small>
                            <label for=""
                                class="form-control fw-bolder">{{ $_subject->curriculum_subject->subject->subject_code }}</label>
                        </div>
                        <div class="col-md">
                            <small for="" class="form-label">COURSE DESCRIPTIVE TITLE</small>
                            <label for=""
                                class="form-control fw-bolder">{{ $_subject->curriculum_subject->subject->subject_name }}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <small for="" class="form-label">COURSE CREDITS</small>
                            <label for="" class="form-control fw-bolder">
                                {{ $_subject->curriculum_subject->subject->units . ($_subject->curriculum_subject->subject->units > 1 ? ' units' : ' unit') }}
                            </label>
                        </div>
                        <div class="col-md">
                            <small for="" class="form-label">LECTURE HOURS PER WEEK</small>
                            <label for=""
                                class="form-control fw-bolder">{{ $_subject->curriculum_subject->subject->lecture_hours . ($_subject->curriculum_subject->subject->lecture_hours > 1 ? ' hours' : ' hour') }}</label>
                        </div>
                        <div class="col-md">
                            <small for="" class="form-label">LABORATORY HOURS PER WEEK</small>
                            <label for=""
                                class="form-control fw-bolder">{{ $_subject->curriculum_subject->subject->laboratory_hours . ($_subject->curriculum_subject->subject->laboratory_hours > 1 ? ' hours' : ' hour') }}</label>
                        </div>

                    </div>
                    <div class="form-group">
                        <small for="" class="form-label">PROGRAM EDUCATIONAL OBJECTIVE <span
                                class="text-danger">*</span></small>
                        <input type="text" class="form-control">
                        @error('program_educational_objective')
                            <label for="" class="badge bg-danger text-small mt-2">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="form-group">
                        <small for="" class="form-label">PROGRAM DESCRIPTION
                            <span class="text-danger">*</span></small>
                        <input type="text" class="form-control">
                        @error('program_educational_objective')
                            <label for="" class="badge bg-danger text-small mt-2">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="form-group">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $("#txtEditor-1").Editor();
            $("#txtEditor-2").Editor();
        });
    </script>
@endsection
