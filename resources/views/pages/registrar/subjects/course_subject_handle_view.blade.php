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


                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="mt-2 text-primary">SECTION LIST</h3>
                </div>
                <div class="card-body">
                    @foreach ($_subject->subject_class as $_data)
                        <p class="mb-0 h4 text-primary">
                            {{ strtoupper($_data->staff->user->name) }}
                            <small class="text-muted"> - {{ $_data->section->section_name }}</small>
                        </p>
                        <form action="{{ route('registrar.class-schedule') }}" method="post" class="mt-4">
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
                        <div class="table-responsive mt-4">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th colspan="4">SCHEDULE</th>
                                    </tr>
                                    <tr>
                                        <th>DAY</th>
                                        <th>START TIME</th>
                                        <th>END TIME</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($_data->class_schedule) > 0)
                                        @foreach ($_data->class_schedule as $_schedule)
                                            <tr>
                                                <td>{{ $_schedule->day }}</td>
                                                <td>{{ $_schedule->start_time }}</td>
                                                <td>{{ $_schedule->end_time }}</td>
                                                <td>
                                                    {{-- <a href="" class="btn btn-primary btn-sm"> <svg width="15"
                                                            viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M9.3764 20.0279L18.1628 8.66544C18.6403 8.0527 18.8101 7.3443 18.6509 6.62299C18.513 5.96726 18.1097 5.34377 17.5049 4.87078L16.0299 3.69906C14.7459 2.67784 13.1541 2.78534 12.2415 3.95706L11.2546 5.23735C11.1273 5.39752 11.1591 5.63401 11.3183 5.76301C11.3183 5.76301 13.812 7.76246 13.8651 7.80546C14.0349 7.96671 14.1622 8.1817 14.1941 8.43969C14.2471 8.94493 13.8969 9.41792 13.377 9.48242C13.1329 9.51467 12.8994 9.43942 12.7297 9.29967L10.1086 7.21422C9.98126 7.11855 9.79025 7.13898 9.68413 7.26797L3.45514 15.3303C3.0519 15.8355 2.91395 16.4912 3.0519 17.1255L3.84777 20.5761C3.89021 20.7589 4.04939 20.8879 4.24039 20.8879L7.74222 20.8449C8.37891 20.8341 8.97316 20.5439 9.3764 20.0279ZM14.2797 18.9533H19.9898C20.5469 18.9533 21 19.4123 21 19.9766C21 20.5421 20.5469 21 19.9898 21H14.2797C13.7226 21 13.2695 20.5421 13.2695 19.9766C13.2695 19.4123 13.7226 18.9533 14.2797 18.9533Z"
                                                                fill="currentColor"></path>
                                                        </svg> </a> --}}
                                                    <a href="{{ route('registrar.class-schedule-remove') }}?_schedule={{ base64_encode($_schedule->id) }}"
                                                        class="btn btn-danger btn-sm"> <svg width="15" viewBox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M20.2871 5.24297C20.6761 5.24297 21 5.56596 21 5.97696V6.35696C21 6.75795 20.6761 7.09095 20.2871 7.09095H3.71385C3.32386 7.09095 3 6.75795 3 6.35696V5.97696C3 5.56596 3.32386 5.24297 3.71385 5.24297H6.62957C7.22185 5.24297 7.7373 4.82197 7.87054 4.22798L8.02323 3.54598C8.26054 2.61699 9.0415 2 9.93527 2H14.0647C14.9488 2 15.7385 2.61699 15.967 3.49699L16.1304 4.22698C16.2627 4.82197 16.7781 5.24297 17.3714 5.24297H20.2871ZM18.8058 19.134C19.1102 16.2971 19.6432 9.55712 19.6432 9.48913C19.6626 9.28313 19.5955 9.08813 19.4623 8.93113C19.3193 8.78413 19.1384 8.69713 18.9391 8.69713H5.06852C4.86818 8.69713 4.67756 8.78413 4.54529 8.93113C4.41108 9.08813 4.34494 9.28313 4.35467 9.48913C4.35646 9.50162 4.37558 9.73903 4.40755 10.1359C4.54958 11.8992 4.94517 16.8102 5.20079 19.134C5.38168 20.846 6.50498 21.922 8.13206 21.961C9.38763 21.99 10.6811 22 12.0038 22C13.2496 22 14.5149 21.99 15.8094 21.961C17.4929 21.932 18.6152 20.875 18.8058 19.134Z"
                                                                fill="currentColor"></path>
                                                        </svg> </a>
                                                </td>
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
