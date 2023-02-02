@extends('layouts.app-main')
@php
$_title = 'Students';
@endphp
@section('page-title', 'Students')
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
        <div class="col-md-5">
            <form action="" method="get" class="card">
                <div class="card-body">
                    <label class="text-muted h5">| SEARCH STUDENT</label>
                    <div class="form-group">
                        <small class="text-primary">COURSE</small>
                        <select name="_course" id="" class="form-select">
                            @foreach ($_course as $course)
                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <small class="text-primary">STUDENT NAME</small>
                        <input type="text" class="form-control" name="_student">


                    </div>
                    <p class="text-muted h6"> Format to search: Last name then use a coma to separate the
                        First Name </p>
                </div>
            </form>
            <form action="/administrator/students/imports" method="post" class="card" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <label class="text-muted h5">| IMPORT STUDENT DETAILS</label>
                    <div class="form-group">
                        <label class="text-success">FILE TYPE</label>
                        <select name="_file_type" id="" class="form-select">
                            <option value="shipboard">Shipboard Update</option>
                            <option value="excel">Excel File</option>
                            <option value="json">Json File</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-success">ATTACH FILE</label>
                        <input type="file" class="form-control" name="_file">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">IMPORT</button>

                </div>
            </form>
        </div>
        <div class="col-md-7">
            @if (count($_students) > 0)
                @foreach ($_students as $_student)
                    @if ($_student)
                        <a href="/administrator/students/view?student={{ base64_encode($_student->id) }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span><b>{{ $_student->account ? $_student->account->student_number : '-' }}</b></span>
                                            <div class="mt-2">
                                                <h2 class="counter" style="visibility: visible;">
                                                    {{ strtoupper($_student->last_name . ', ' . $_student->first_name) }}
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <div>
                                            <span>{{ $_student->account ? $_student->account->email : '-' }}</span>
                                            <br>
                                            <span
                                                class="badge bg-primary">{{ $_student->enrollment_assessment ? $_student->enrollment_assessment->course->course_name : '-' }}</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
            @else
                <div>
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="___class_+?24___">
                                <h4 class="text-muted">| No Such Data</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
