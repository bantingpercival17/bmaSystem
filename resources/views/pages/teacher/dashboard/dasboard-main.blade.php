@extends('layouts.app-main')
@php
$_title = 'Subjects';
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
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
        @if ($_subject->count() > 0)
            @foreach ($_subject as $subject)
                <div class=" col-sm-4 col-md-4">
                    <a href="{{ route('teacher.subject-view') }}?_subject={{ base64_encode($subject->id) }}">
                        <div class="card">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md">
                                        <span class="h3 text-primary">
                                            <b>
                                                {{ $subject->curriculum_subject->subject->subject_code }}
                                            </b>
                                        </span>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                </div>
                                <small class="badge bg-secondary"><b> {{ $subject->section->section_name }}</b></small>

                                @if ($subject->class_schedule)
                                    @foreach ($subject->class_schedule as $item)
                                        <p class="m-0 p-0">
                                            <small class="text-primary">
                                                <span class="fw-bolder">{{ $item->day }}</span> <span
                                                    class="text-muted">{{ $item->start_time }} -
                                                    {{ $item->end_time }}</span>
                                            </small>
                                        </p>
                                    @endforeach
                                @else
                                    <small class="text-muted">
                                        EMPTY CLASS SCHEDULED
                                    </small>
                                @endif



                            </div>
                        </div>
                    </a>

                </div>
            @endforeach
        @else
            <div class="col-12 col-sm-4 col-md-4">
                <div class="card card-primary ">
                    <div class="card-body box-profile">
                        <div>
                            <h4 class="text-info">No Assigned Subjects</h4>
                        </div>
                        <p class="text-muted ">
                        </p>

                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
