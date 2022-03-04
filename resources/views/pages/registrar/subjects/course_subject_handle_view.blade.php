@extends('layouts.app-main')
@php
$_title = 'Subjects';
@endphp
@section('page-title', 'Subjects')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('registrar.subject-view') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Subjects
        </a>
    </li>
    <li class="breadcrumb-item">
        <a
            href="{{ route('registrar.course-subject-view') . '?_course=' . base64_encode($_subject->course->id) }} {{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
            {{ $_subject->course->course_name }}
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_subject->subject->subject_name }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <label class="card-title">
                        <b
                            class="h4">{{ ucwords(strtolower($_subject->subject->subject_code . ' - ' . $_subject->subject->subject_name)) }}</b>
                        <br>
                        {{ $_subject->course->course_name }}
                    </label>
                </div>
                <div class="card-body">
                    <form action="{{ route('registrar.classes-handled') }}" method="post">
                        @csrf
                        <input type="hidden" name="_subject" value="{{ $_subject->id }}">
                        <input type="hidden" name="_academic" value="{{ Auth::user()->staff->current_academic()->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Instructor</label>
                                    <select name="_teacher" class="form-select">
                                        @foreach ($_teachers as $teacher)
                                            <option value="{{ $teacher->staff->id }}">
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Section</label>
                                    <select name="_section" class="form-select">
                                        @if (count($_subject->course->section([Auth::user()->staff->current_academic()->id, $_subject->year_level])->get()) > 0)
                                            @foreach ($_subject->course->section([Auth::user()->staff->current_academic()->id, $_subject->year_level])->get() as $_section)
                                                <option value="{{ $_section->id }}">
                                                    {{ $_section->section_name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option selected disabled>
                                                Please Create Section
                                            </option>
                                        @endif


                                    </select>
                                </div>
                            </div>

                        </div>
                        <label for="" class="form-label">Subject Schedule</label>
                        <div class="row">
                            <div class="col-md">
                                @php
                                    $_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                                @endphp
                                <label for="" class="form-label">Weekday</label>
                                <select name="_week" id="" class="form-select">
                                    @foreach ($_week as $week)
                                        <option value="{{ $week }}">{{ $week }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Start Time</label>
                                        <input type="time" name="_start" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">End Time</label>
                                        <input type="time" name="_end" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-info text-white w-100 mt-2" type="submit">Submit</button>

                    </form>
                    <h3 class="mt-2 text-primary">Section List</h3>
                    <hr>
                    @foreach ($_subject->subject_class as $_data)
                        <div class="twit-feed">
                            <div class="d-flex align-items-center mb-4">
                                <div class="media-support-info">
                                    <p class="mb-0 h4 text-primary">
                                        {{ $_data->staff->user->name }} - <small
                                            class="text-muted">{{ $_data->section->section_name }}</small>
                                    </p>
                                    <p class="mb-0">
                                        SCHEDULE:

                                        @if (count($_data->class_schedule) > 0)
                                            @foreach ($_data->class_schedule as $_schedule)
                                                <p> <span class="text-info">{{ $_schedule->day }}</span> -
                                                    {{ $_schedule->start_time }} : {{ $_schedule->end_time }} </p>
                                            @endforeach
                                        @else
                                            <p> No Schedule </p>
                                        @endif
                                    </p>
                                    <form action="{{ route('registrar.class-schedule') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="_subject_class" value="{{ $_data->id }}">
                                        <label for="" class="form-label">Subject Schedule</label>
                                        <div class="row">
                                            <div class="col-md">
                                                @php
                                                    $_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                                                @endphp
                                                <label for="">Weekday</label>
                                                <select name="_week" id="" class="form-select form-select-sm">
                                                    @foreach ($_week as $week)
                                                        <option value="{{ $week }}">{{ $week }}</option>
                                                    @endforeach

                                                </select>
                                                @error('_week')
                                                    <span class="badge bg-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md">
                                                <label for="">Start Time</label>
                                                <input type="time" name="_start" class="form-control form-control-sm">
                                                @error('_start')
                                                    <span class="badge bg-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md">
                                                <label for="">End Time</label>
                                                <input type="time" name="_end" class="form-control form-control-sm">
                                                @error('_end')
                                                    <span class="badge bg-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <button class="btn btn-info text-white w-100 mt-2" type="submit">Submit</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <label class="card-title text-muted">
                        <b>{{ $_subject->course_id == 3 ? 'GRADE ' . $_subject->year_level : $_subject->year_level . ' CLASS' }}
                        </b><br>
                        <small>Subject List</small>
                    </label>
                </div>
                <div class="card-body">
                    @if ($_subject = $_subject->course->course_subject([$_subject->curriculum_id, $_subject->year_level, Auth::user()->staff->current_academic()->semester]))
                        @foreach ($_subject as $_subject)
                            <a
                                href="{{ route('registrar.course-subject-handle-view') }}?_subject={{ base64_encode($_subject->id) }}">

                                <div class="twit-feed">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="media-support-info">
                                            <h6 class="mb-0"> {{ $_subject->subject_code }}</h6>
                                            <p class="mb-0">
                                                {{ $_subject->subject_name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <hr class="my-4">
                        @endforeach
                    @else
                        <tr>
                            <td>NO SUBJECT</td>
                        </tr>
                    @endif


                </div>

            </div>
        </div>
    </div>
@endsection
