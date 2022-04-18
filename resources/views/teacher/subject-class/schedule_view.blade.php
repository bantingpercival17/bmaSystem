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
        <div class="row">
            <div class="col-8">
                @if (Auth::user()->email == 'k.j.cruz@bma.edu.ph')
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Create Weekly Lesson Log</h4>
                                <small class="fw-bolder">FORM 4 </small>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (count($_subject->class_schedule) > 0)
                                <form action="{{ route('teacher.weekly-lesson-log') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">WEEK</label>
                                        <input type="week" class="form-control" name="_week" value="{{ old('_week') }}">
                                        @error('_week')
                                            <span class="badge bg-danger mt-2">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @foreach ($_subject->class_schedule as $_key => $_schedule)
                                        <label for="" class="h4 fw-bolder">{{ $_schedule->day }}
                                        </label>
                                        <small>{{ $_schedule->start_time . ' - ' . $_schedule->end_time }}</small>
                                        <div class="form">
                                            <div class="form-group">
                                                <label class="form-label">TOPIC</label>
                                                <input type="text" class="form-control"
                                                    name="_topic_{{ $_schedule->day }}" value="{{ old('_topic.*') }}">
                                                @error('_topic.*')
                                                    <span class="badge bg-danger mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">TYPE OF DISCUSSION</label>
                                                <select id="" class="form-select" name="_disscussion[]">
                                                    <option value="laboratory">LABORATORY DISSCUSSION</option>
                                                    <option value="lecture">LECTURE DISSCUSSION</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">LEARNING ACTIVITY / LABORATORY
                                                    EXERCISE</label>
                                                <textarea id="" cols="30" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">DURATION</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">GROUPING</label>
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">REMAINING GROUP</label>
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                    <button class="btn btn-primary w-100" type="submit">CREATE</button>
                                </form>
                            @else
                                <span class="text-muted fw-bolder">No Weekly Lesson Log</span>
                            @endif
                        </div>
                    </div>
                @endif


            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Class Schedule</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="basic-table" class="table table-striped mb-0" role="grid">
                            <thead>
                                <tr>
                                    <th>DAY</th>
                                    <th>START TIME</th>
                                    <th>END TIME</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($_subject->class_schedule) > 0)
                                    @foreach ($_subject->class_schedule as $_schedule)
                                        <tr>
                                            <td>{{ $_schedule->day }}</td>
                                            <td>{{ $_schedule->start_time }}</td>
                                            <td>{{ $_schedule->end_time }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">No Schedule</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection
