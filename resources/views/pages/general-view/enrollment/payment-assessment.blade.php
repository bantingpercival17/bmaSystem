@extends('layouts.app-main')
@php
$_title = 'Payment Assessment';
@endphp
@section('page-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item ">
        <a href="/">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Dashboard
        </a>

    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_title }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        @foreach ($_courses as $course)
            <div class="col-md">
                <a
                    href="{{ route('enrollment.payment-assessment') }}?_course={{ base64_encode($course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                    <div class="card  iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0"
                        data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="counter">
                                    {{ count($course->payment_assessment) }}
                                </h3>
                                <svg width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.8877 10.8967C19.2827 10.7007 20.3567 9.50473 20.3597 8.05573C20.3597 6.62773 19.3187 5.44373 17.9537 5.21973"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                    <path
                                        d="M19.7285 14.2505C21.0795 14.4525 22.0225 14.9255 22.0225 15.9005C22.0225 16.5715 21.5785 17.0075 20.8605 17.2815"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.8867 14.6638C8.67273 14.6638 5.92773 15.1508 5.92773 17.0958C5.92773 19.0398 8.65573 19.5408 11.8867 19.5408C15.1007 19.5408 17.8447 19.0588 17.8447 17.1128C17.8447 15.1668 15.1177 14.6638 11.8867 14.6638Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.8869 11.888C13.9959 11.888 15.7059 10.179 15.7059 8.069C15.7059 5.96 13.9959 4.25 11.8869 4.25C9.7779 4.25 8.0679 5.96 8.0679 8.069C8.0599 10.171 9.7569 11.881 11.8589 11.888H11.8869Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                    <path
                                        d="M5.88509 10.8967C4.48909 10.7007 3.41609 9.50473 3.41309 8.05573C3.41309 6.62773 4.45409 5.44373 5.81909 5.21973"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                    <path
                                        d="M4.044 14.2505C2.693 14.4525 1.75 14.9255 1.75 15.9005C1.75 16.5715 2.194 17.0075 2.912 17.2815"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                </svg>

                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h5 class="text-primary">
                                    {{ $course->course_code }}
                                </h5>

                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-8">
            <form action="{{ request()->url() }}" method="get">
                <input type="hidden" name="_course" value="{{ base64_encode($_course->id) }}">
                @if (request()->input('_academic'))
                    <input type="hidden" name="_academic" value="{{ request()->input('_academic') }}">
                @endif
                @if (request()->input('_year_level'))
                    <input type="hidden" name="_year_level" value="{{ request()->input('_year_level') }}">
                @endif
                @if (request()->input('_sort'))
                    <input type="hidden" name="_sort" value="{{ request()->input('_sort') }}">
                @endif
                <div class="row">
                    <div class="col-6">
                        <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                        <div class="form-group search-input">
                            <input type="search" class="form-control" placeholder="Search Patteran: Lastname, Firstname"
                                name="_students">
                        </div>
                    </div>
                    <div class="col-4">
                        <small class="text-primary"><b>SORT BY</b></small>
                        <div class="form-group search-input">
                            <select name="_sort" class="form-select">
                                <option value="enrollment-date">Enrollment Date</option>
                                <option value="student-number">Student Number</option>
                                <option value="lastname">Lastname</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">FIND</button>
                        </div>
                    </div>
                </div>


            </form>
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <span class="fw-bolder">
                        {{ $_course->course_name }}
                    </span>
                    <div class="d-flex justify-content-between">
                        @if (request()->input('_sort'))
                            <div>
                                <small>Sort By: </small> <span
                                    class="fw-bolder text-info">{{ ucwords(str_replace('-', ' ', request()->input('_sort'))) }}</span>
                            </div>
                        @endif
                        @if (request()->input('_year_level'))
                            <div class="ms-5">
                                <small>Year Level Result: </small> <span
                                    class="fw-bolder text-info">{{ request()->input('_year_level') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <span class="text-muted h6">
                    No. Result: <b>{{ count($_students) }}</b>
                </span>
            </div>
            @if (count($_students) > 0)
                @foreach ($_students as $_data)
                    <div class="card mb-2">
                        <div class="row no-gutters">
                            {{-- <div class="col-md-3">
                                <img src="{{ $_data ? $_data->student->profile_pic($_data->student->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                    class="card-img" alt="#">
                            </div> --}}
                            <div class="col-md">
                                <div class="card-body p-3 me-2">
                                    <label for=""
                                        class="fw-bolder text-primary h4">{{ $_data ? strtoupper($_data->student->last_name . ', ' . $_data->student->first_name) : 'MIDSHIPMAN NAME' }}</label>
                                    <p class="mb-0">
                                        <small class="fw-bolder badge bg-secondary">
                                            {{ $_data ? ($_data->student->account ? $_data->student->account->student_number : 'STUDENT NO.') : 'NEW STUDENT' }}
                                        </small> |
                                        <small class="fw-bolder badge bg-secondary">
                                            {{ $_data ? ($_data->student->enrollment_status ? strtoupper(Auth::user()->staff->convert_year_level($_data->student->enrollment_status->year_level)) : 'YEAR LEVEL') : 'YEAR LEVEL' }}
                                        </small> |
                                        <small class="fw-bolder badge bg-secondary">
                                            {{ $_data ? ($_data->student->enrollment_status ? $_data->student->enrollment_status->course->course_name : 'COURSE') : 'COURSE' }}
                                        </small>
                                    </p>
                                    <div class="row mt-0">

                                        <div class="col-md">
                                            <small class="fw-bolder text-muted">CURRICULUM:</small> <br>
                                            <small class="badge bg-primary">
                                                {{ $_data ? ($_data->student->enrollment_status ? strtoupper($_data->student->enrollment_status->curriculum->curriculum_name) : 'CURRICULUM') : 'CURRICULUM' }}
                                            </small>
                                        </div>
                                        <div class="col-md">
                                            <small class="fw-bolder text-muted">SECTION:</small> <br>
                                            <small class="badge bg-primary">
                                                {{ $_data ? ($_data->student->enrollment_status ? strtoupper($_data->student->enrollment_status->academic->semester . ' | ' . $_data->student->enrollment_status->academic->school_year) : 'SECTION') : 'SECTION' }}
                                            </small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between ">
                                <div>
                                    <span><b>{{ $_data->student->account ? $_data->student->account->student_number : '-' }}</b></span>
                                    <a
                                        href="{{ route('registrar.student-profile') }}?_student={{ base64_encode($_data->student->id) }}">
                                        <div class="mt-2">
                                            <h2 class="counter" style="visibility: visible;">
                                                {{ strtoupper($_data->student->last_name . ', ' . $_data->student->first_name) }}
                                            </h2>
                                        </div>

                                    </a>
                                    <span>{{ $_data->student->account ? $_data->student->account->email : '-' }}</span>
                                    <br>
                                    <span
                                        class="badge bg-primary">{{ $_data->student->enrollment_assessment->course->course_name }}</span>

                                </div>
                                <div>
                                    <div class="badge bg-primary">
                                        <span>{{ $_data->student->enrollment_status->payment_assessments->payment_assessment_paid->created_at->format('F d, Y') }}</span>
                                    </div>

                                    <a href="{{ route('registrar.student-information-report') }}?_assessment={{ base64_encode($_data->student->enrollment_assessment->id) }}"
                                        class="badge bg-info text-white"> <svg width="18" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.2301 7.29052V3.2815C11.2301 2.85523 11.5701 2.5 12.0001 2.5C12.3851 2.5 12.7113 2.79849 12.763 3.17658L12.7701 3.2815V7.29052L17.55 7.29083C19.93 7.29083 21.8853 9.23978 21.9951 11.6704L22 11.8861V16.9254C22 19.373 20.1127 21.3822 17.768 21.495L17.56 21.5H6.44C4.06 21.5 2.11409 19.5608 2.00484 17.1213L2 16.9047L2 11.8758C2 9.4281 3.87791 7.40921 6.22199 7.29585L6.43 7.29083H11.23V13.6932L9.63 12.041C9.33 11.7312 8.84 11.7312 8.54 12.041C8.39 12.1959 8.32 12.4024 8.32 12.6089C8.32 12.7659 8.3648 12.9295 8.45952 13.0679L8.54 13.1666L11.45 16.1819C11.59 16.3368 11.79 16.4194 12 16.4194C12.1667 16.4194 12.3333 16.362 12.4653 16.2533L12.54 16.1819L15.45 13.1666C15.75 12.8568 15.75 12.3508 15.45 12.041C15.1773 11.7594 14.7475 11.7338 14.4462 11.9642L14.36 12.041L12.77 13.6932V7.29083L11.2301 7.29052Z"
                                                fill="currentColor"></path>
                                        </svg> </a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                @endforeach
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="mt-2">
                                    <h2 class="counter" style="visibility: visible;">
                                        NO DATA
                                    </h2>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-4">
            {{-- <div class="d-flex justify-content-between mb-3">
                <div>
                    <span class="fw-bolder">
                        Export File :
                    </span>

                </div>
                <div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('enrollment.enrolled-list-report') }}?_course={{ base64_encode($_course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}&_report=excel-report"
                            class="btn btn-primary btn-sm">Excel</a>
                        <a href="{{ route('enrollment.enrolled-list-report') }}?_course={{ base64_encode($_course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}&_report=pdf-report"
                            class="btn btn-danger btn-sm ms-2">PDF</a>
                    </div>
                </div>
            </div> --}}

            @php
                $_level = [11, 12];
                $_level = $_course->id == 3 ? $_level : [1, 2, 3, 4];
            @endphp
            @foreach ($_level as $level)
                <div class="col-md">
                    <a
                        href="{{ request()->url() }}?_course={{ base64_encode($_course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}&_year_level={{ $level }}">
                        <div class="card  iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0"
                            data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h5 class="text-primary">
                                        @if ($_course->id == 3)
                                            Grade {{ $level }}
                                        @else
                                            {{ $_course->course_code . ' ' . $level }}/C
                                        @endif
                                    </h5>
                                    <svg width="32" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.8877 10.8967C19.2827 10.7007 20.3567 9.50473 20.3597 8.05573C20.3597 6.62773 19.3187 5.44373 17.9537 5.21973"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path
                                            d="M19.7285 14.2505C21.0795 14.4525 22.0225 14.9255 22.0225 15.9005C22.0225 16.5715 21.5785 17.0075 20.8605 17.2815"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.8867 14.6638C8.67273 14.6638 5.92773 15.1508 5.92773 17.0958C5.92773 19.0398 8.65573 19.5408 11.8867 19.5408C15.1007 19.5408 17.8447 19.0588 17.8447 17.1128C17.8447 15.1668 15.1177 14.6638 11.8867 14.6638Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.8869 11.888C13.9959 11.888 15.7059 10.179 15.7059 8.069C15.7059 5.96 13.9959 4.25 11.8869 4.25C9.7779 4.25 8.0679 5.96 8.0679 8.069C8.0599 10.171 9.7569 11.881 11.8589 11.888H11.8869Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path
                                            d="M5.88509 10.8967C4.48909 10.7007 3.41609 9.50473 3.41309 8.05573C3.41309 6.62773 4.45409 5.44373 5.81909 5.21973"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path
                                            d="M4.044 14.2505C2.693 14.4525 1.75 14.9255 1.75 15.9005C1.75 16.5715 2.194 17.0075 2.912 17.2815"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                    </svg>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="conter">{{ count($_course->payment_assessment_sort($level)->get()) }}</h3>
                                    {{-- <p class="mb-0 ms-2">+3 last/d</p> --}}
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            @endforeach
        </div>

    </div>
@endsection
