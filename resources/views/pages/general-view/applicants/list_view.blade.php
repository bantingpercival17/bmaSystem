@extends('layouts.app-main')
@php
$_url_role = ['dashboard', 'administrator/applicants', 'accounting/applicants', 'registrar/applicants'];
$_course_enrolled = ['admin.applicant-lists', 'admin.applicant-lists', 'accounting.course-enrolled', 'registrar.course-enrolled'];
$_applicant_view = ['admin.applicant-profile', 'admin.applicant-profile', 'admin.applicant-profile', 'admin.applicant-profile'];
$_course_url = route($_course_enrolled[0]);
$_profile_link = route($_applicant_view[0]);
foreach ($_url_role as $key => $_data) {
    $_course_url = request()->is($_data . '*') ? route($_course_enrolled[$key]) : $_course_url;
    $_profile_link = request()->is($_data . '*') ? route($_applicant_view[$key]) : $_profile_link;
}
$_title = 'Applicant List';
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
                                <option value="applicant-number">Applicant Number</option>
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
                    No. Result: <b>{{ count($_applicants) }}</b>
                </span>
            </div>
            @if (count($_applicants) > 0)
                @foreach ($_applicants as $_data)
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span><b>{{ $_data->applicant ? $_data->applicant_number : '-' }}</b></span>
                                    <a href="{{ $_profile_link }}?_student={{ base64_encode($_data->id) }}">
                                        <div class="mt-2">
                                            <h2 class="counter" style="visibility: visible;">
                                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                                            </h2>
                                        </div>

                                    </a>
                                    <span>{{ $_data->applicant ? $_data->email : '-' }}</span>
                                    <br>
                                    <span class="badge bg-primary">{{ $_data->course->course_name }}</span>

                                </div>
                                <div>
                                    <div class="badge bg-primary">
                                        <span>{{ $_data->created_at->format('F d, Y') }}</span>
                                    </div>

                                    <a href="{{ route('registrar.student-information-report') }}?_assessment={{ base64_encode($_data->id) }}"
                                        class="badge bg-info text-white"> <svg width="18" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.2301 7.29052V3.2815C11.2301 2.85523 11.5701 2.5 12.0001 2.5C12.3851 2.5 12.7113 2.79849 12.763 3.17658L12.7701 3.2815V7.29052L17.55 7.29083C19.93 7.29083 21.8853 9.23978 21.9951 11.6704L22 11.8861V16.9254C22 19.373 20.1127 21.3822 17.768 21.495L17.56 21.5H6.44C4.06 21.5 2.11409 19.5608 2.00484 17.1213L2 16.9047L2 11.8758C2 9.4281 3.87791 7.40921 6.22199 7.29585L6.43 7.29083H11.23V13.6932L9.63 12.041C9.33 11.7312 8.84 11.7312 8.54 12.041C8.39 12.1959 8.32 12.4024 8.32 12.6089C8.32 12.7659 8.3648 12.9295 8.45952 13.0679L8.54 13.1666L11.45 16.1819C11.59 16.3368 11.79 16.4194 12 16.4194C12.1667 16.4194 12.3333 16.362 12.4653 16.2533L12.54 16.1819L15.45 13.1666C15.75 12.8568 15.75 12.3508 15.45 12.041C15.1773 11.7594 14.7475 11.7338 14.4462 11.9642L14.36 12.041L12.77 13.6932V7.29083L11.2301 7.29052Z"
                                                fill="currentColor"></path>
                                        </svg> </a>
                                </div>
                            </div>
                        </div>
                    </div>
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
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <span class="fw-bolder">
                        Export File :
                    </span>

                </div>
                <div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ request()->url() }}/report?_course={{ base64_encode($_course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}&_report=excel-report"
                            class="btn btn-primary btn-sm">Excel</a>
                        <a href="" class="btn btn-danger btn-sm ms-2">PDF</a>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
