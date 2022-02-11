@extends('layouts.app-main')
@php
$_title = 'Grade Submission';
@endphp
@section('page-title', $_title)
@section('page-mode', 'dark-mode')
@section('beardcrumb-content')
    @if (request()->input('_academic'))
        <li class="breadcrumb-item">
            <a href="{{ route('department-head.grade-submission') }}">
                <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>{{ $_title }}</a>
        </li>

        <li class="breadcrumb-item active" aria-current="page">
            {{ Auth::user()->staff->current_academic()->semester .' | ' .Auth::user()->staff->current_academic()->school_year }}
        </li>
    @else
        <li class="breadcrumb-item active" aria-current="page">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </li>
    @endif

@endsection
@section('page-content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Instruction List</h4>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive mt-4">
                        <table id="basic-table" class="table table-striped mb-0" role="grid">
                            <thead>
                                <tr>
                                    <th>Instruction Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (count($_staffs) > 0)
                                    @foreach ($_staffs as $_data)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center text-primary">
                                                    <img class=" avatar-rounded img-fluid avatar-45 me-3 bg-soft-primary"
                                                        src="{{ asset($_data->profile_pic($_data)) }}" alt="profile">
                                                    <a
                                                        href="{{ route('department-head.grade-submission-view') . '?_staff=' . base64_encode($_data->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                                                        {{ strtoupper($_data->first_name . ' ' . $_data->last_name) }}
                                                    </a>
                                                </div>

                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md">
                                                        <small class="fw-bolder">MIDTERM GRADE SUBMISSION: </small>
                                                        <br>
                                                        <span class="fw-bolder">
                                                            <span
                                                                class="badge bg-info">{{ count($_data->grade_submission_midterm) }}</span>
                                                            <small> out of</small>
                                                            <span class="badge bg-primary">
                                                                {{ count($_data->subject_handles) }}
                                                            </span>
                                                            <small>Submitted Grade</small>
                                                        </span>
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="fw-bolder">FINAL GRADE SUBMISSION: </small> <br>
                                                        <span class="fw-bolder">
                                                            <span
                                                                class="badge bg-info">{{ count($_data->grade_submission_finals) }}</span>
                                                            <small> out of</small>
                                                            <span class="badge bg-primary">
                                                                {{ count($_data->subject_handles) }}
                                                            </span>
                                                            <small>Submitted Grade</small>
                                                        </span>
                                                    </div>
                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach
                                @else

                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
