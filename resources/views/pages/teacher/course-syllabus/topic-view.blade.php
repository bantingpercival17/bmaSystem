@extends('layouts.app-main')
@php
$_title = 'Topic View';
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
    <li class="breadcrumb-item">
        <a href="{{ route('teacher.course-syllabus') }}">
            Subject Name</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $_title }}</li>
@endsection
@section('page-content')
    <div class="content">

        <div class="row">
            <div class="col-md">
                <p class="display-6 text-primary">
                    {{ $_topic->learning_outcome }}
                </p>
                <div class="card">
                    <div class="card-body">
                        <iframe src="{{ $_topic->materials->presentation_link }}" frameborder="0" width="100%" height="485"
                            allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
                        {{-- <iframe src="{{ $_subject_lesson['presentation'] }}" frameborder="0"></iframe> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <label for="" class="fw-bolder">LESSONS</label>
                    </div>
                   {{--  <div class="card-body">
                        @if (count($_subject_content) > 0)
                            @foreach ($_subject_content as $key => $item)
                                <div class="learning-objective mt-0 p-2">
                                    @foreach ($item['learning_outcome'] as $count => $learning_outcome)
                                        <div class="alert alert-left alert-info alert-dismissible fade show" role="alert">
                                            <a
                                                href="{{ route('academic.subject-lesson') }}?_subject={{ request()->input('_subject') }}_index={{ $key }}&_content={{ $count }}">
                                                <span class="fw-bolder text-nuted">{{ $learning_outcome['topic'] }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-left alert-secondary alert-dismissible fade show" role="alert">
                                This Subject is under maintaince..
                            </div>
                        @endif
                    </div> --}}
                </div>

            </div>
        </div>
    </div>
@endsection
