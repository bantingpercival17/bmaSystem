@extends('layouts.app-main')
@php
$_title = 'Subject: ' . $_course_syllabus->subject->subject_code;
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
                <div class="row">
                    <div class="col-md-2">
                        <small>COURSE CODE</small> <br>
                        <label for="" class="fw-bolder">{{ $_course_syllabus->subject->subject_code }}</label>
                    </div>
                    <div class="col-md">
                        <small>COURSE DESCRIPTIVE TITLE</small> <br>
                        <label for="" class="fw-bolder">{{ $_course_syllabus->subject->subject_name }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <small>COURSE CREDITS</small> <br>
                        <label for="" class="fw-bolder">{{ $_course_syllabus->subject->units }}
                            UNIT/S</label>
                    </div>
                    <div class="col-md">
                        <small>LECTURE HOURS</small> <br>
                        <label for="" class="fw-bolder">{{ $_course_syllabus->subject->lecture_hours }}
                            HOUR/S</label>
                    </div>
                    <div class="col-md">
                        <small>LABORATORY HOURS</small> <br>
                        <label for="" class="fw-bolder">{{ $_course_syllabus->subject->laboratory_hours }}
                            HOUR/S</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <small>COURSE DESCRIPTION</small> <br>
                        <label for="" class="fw-bolder">{{ $_course_syllabus->course_description }}</label>
                    </div>
                </div>
            </div>
            {{-- <div class="card-body">
                <div class="">
                    <a
                        href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part1' }}">PART
                        A: COURSE SPECIFICATION</a>
                </div>
                <div class="">
                    <a
                        href="{{ route('teacher.course-syllabus-editor') . '?course_syllabus=' . request()->input('course_syllabus') . '&part=part2' }}">
                        PART B: COURSE OUTLINE AND TIMETABLE
                        <br>
                        PAER C: COURSE TOPICS
                    </a>
                </div>
            </div> --}}
            <div class="card-body">
                @php
                    $_array_tab = [['A', 'part-one', 'COURSE SPECIFICATION'], ['B', 'part-two', 'COURSE OUTLINE AND TIMETABLE'],['C', 'part-three', 'COURSE SYLLABUS']];
                @endphp
                @include('pages.teacher.course-syllabus.part-tab-layouts.part-one')
                @include('pages.teacher.course-syllabus.part-tab-layouts.part-two')
                @include('pages.teacher.course-syllabus.part-tab-layouts.part-three')
                <ul class="nav nav-tabs nav-fill" id="myTab-three" role="tablist">
                    @foreach ($_array_tab as $key => $tab)
                        <li class="nav-item">
                            <a class="nav-link {{ $key == 0 ? 'active' : '' }}" id="{{ $tab[1] }}"
                                data-bs-toggle="tab" href="#{{ $tab[1] }}-content" role="tab"
                                aria-controls="home" aria-selected="{{ $key == 0 ? true : false }}">PART
                                {{ $tab[0] }}</a>
                        </li>
                    @endforeach

                </ul>
                <div class="tab-content" id="myTabContent-4">
                    @foreach ($_array_tab as $key => $item)
                        <div class="tab-pane fade show {{ $key == 0 ? 'active' : '' }}" id="{{ $item[1] }}-content"
                            role="tabpanel" aria-labelledby="{{ $item[1] }}">
                            <label for="" class="h5 fw-bolder text-primary">{{ $item[2] }}</label>
                            <div class="content-tool float-end">
                                <a href="{{ route('teacher.course-syllabus-report') . '?_course_syllabus=' . base64_encode($_course_syllabus->id) }}&_part={{ base64_encode($item[1]) }}"
                                    class="btn btn-primary btn-sm">GENERATE PART {{ $item[0] }}</a>
                            </div>
                            <div class="content">
                                @yield($item[1])
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script></script>
@endsection
