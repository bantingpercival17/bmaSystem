@extends('layouts.app-main')
@section('page-title', 'Dashboard')
@section('beardcrumb-content')
    <li class="breadcrumb-item">
        <a href="{{ route('registrar.dashboard') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>Dashboard
        </a>

    </li>
    <li class="breadcrumb-item active" aria-current="page">
        Student Not Cleared
    </li>
@endsection
@section('page-content')
    <div class="row">
        @php
            $_level = [11, 12];
            $_level = $_course->id == 3 ? $_level : [1, 2, 3, 4];
        @endphp
        @foreach ($_level as $level)
            @php
                $_payment_ = $_course->students_not_clearance_year_level($level)->get();
            @endphp
            <div class="col-md">
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
                            <a href="javascript:void(0);">
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
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="conter">{{ count($_payment_) }}</h3>
                            {{-- <p class="mb-0 ms-2">+3 last/d</p> --}}
                        </div>
                    </div>
                </div>
                </a>

            </div>
        @endforeach
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Student Not Cleared List</h4>
                <small class="text-muted mt-2 fw-bolder">{{ $_course->course_name }}</small>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive mt-4">
                <table id="basic-table" class="table table-striped mb-0" role="grid">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Year Level</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if (count($_course->students_not_clearance))
                            @foreach ($_course->students_not_clearance as $_payment)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route('registrar.student-clearance') }}?_student={{ base64_encode($_payment->student->id) }}">
                                            {{ $_payment->student->first_name . ' ' . $_payment->student->last_name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ Auth::user()->staff->convert_year_level($_payment->year_level) }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">No Data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
