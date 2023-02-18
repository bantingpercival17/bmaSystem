@extends('layouts.app-main')
@php
    $_title = 'Student';
@endphp
@section('page-title', $_title)
@section('content-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item ">
        <a href="{{ route('admin.students') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Profile</li>
@endsection
@section('page-content')
    <label for="" class="fw-bolder text-primary h4">STUDENT INFORMATION</label>
    <div class="row mt-5">
        <div class="col-md-4">
            <form action="" method="get">
                <div class="col">
                    <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                    <div class="form-group search-input">
                        <input type="search" class="form-control" placeholder="Search Pattern: Lastname, Firstname"
                            name="search_student">
                    </div>
                </div>
            </form>
            <div class=" d-flex justify-content-between mb-2">
                <h6 class=" fw-bolder text-muted">
                    {{ request()->input('_student') ? 'Search Result: ' . request()->input('_student') : 'Recent Student' }}
                </h6>
                <span class="text-primary h6">
                    No. Result: <b>{{ count($_students) }}</b>
                </span>

            </div>
            @if ($_students)
                @foreach ($_students as $item)
                    <div class="card mb-2">
                        <a
                            href="?student={{ base64_encode($item->id) }}{{ request()->input('_payment_category') ? '&_payment_category=' . request()->input('_payment_category') : '' }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                            <div class="row no-gutters">
                                <div class="col-md-4">

                                    <img src="{{ $item->profile_picture() }}" class="avatar-100 rounded card-img"
                                        alt="student-image">
                                </div>
                                <div class="col-md p-1">
                                    <div class="card-body p-2">
                                        <small
                                            class="text-primary fw-bolder">{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</small>
                                        <br>
                                        <small
                                            class="badge {{ $item->enrollment_assessment ? $item->enrollment_assessment->color_course() : 'text-muted' }}">{{ $item->enrollment_assessment ? $item->enrollment_assessment->course->course_code : '-' }}</small>
                                        -
                                        <span>{{ $item->account ? $item->account->student_number : '' }}</span>

                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach
                @if (!request()->input('search_student'))
                    <div class="mb-3">
                        {{ $_students->links() }}
                    </div>
                @endif
            @else
                <div class="card border-bottom border-4 border-0 border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>NO STUDENT</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="col-md-8">
            @if (request()->input('student'))
                <div class="card mb-2">
                    <div class="row no-gutters">
                        <div class="col-md-3">
                            <img src="{{ $_student ? $_student->profile_picture() : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                                class="card-img" alt="#">
                        </div>
                        <div class="col-md ps-0">
                            <div class="card-body p-3 me-2">
                                <label for=""
                                    class="fw-bolder text-primary h4">{{ $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name) : 'MIDSHIPMAN NAME' }}</label>
                                <p class="mb-0">
                                    <small class="fw-bolder badge bg-secondary">
                                        {{ $_student ? ($_student->enrollment_status ? $_student->enrollment_status->course->course_name : 'COURSE') : 'COURSE' }}
                                    </small> -
                                    <small class="badge bg-primary">
                                        {{ $_student ? ($_student->enrollment_status ? strtoupper($_student->enrollment_status->academic->semester . ' | ' . $_student->enrollment_status->academic->school_year) : 'SECTION') : 'SECTION' }}
                                    </small>
                                </p>
                                <p class="mb-0">
                                    <small class="fw-bolder badge bg-secondary">
                                        {{ $_student ? ($_student->account ? $_student->account->student_number : 'STUDENT NO.') : 'NEW STUDENT' }}
                                    </small> -
                                    <small class="fw-bolder badge bg-secondary">
                                        {{ $_student ? ($_student->enrollment_status ? strtoupper(Auth::user()->staff->convert_year_level($_student->enrollment_status->year_level)) : 'YEAR LEVEL') : 'YEAR LEVEL' }}
                                    </small> -
                                    <small class="badge bg-primary">
                                        {{ $_student ? ($_student->enrollment_status ? strtoupper($_student->enrollment_status->curriculum->curriculum_name) : 'CURRICULUM') : 'CURRICULUM' }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <nav class="nav nav-underline bg-soft-primary pb-0 text-center" aria-label="Secondary navigation">

                    <div class="d-flex" id="head-check">
                        <a class="nav-link {{ request()->input('view') == 'profile' || !request()->input('view') ? 'active' : 'text-muted' }}"
                            href="{{ route('registrar.student-profile') }}?student={{ base64_encode($_student->id) }}&view=profile">PROFILE</a>
                        <a class="nav-link  {{ request()->input('view') == 'enrollment' ? 'active' : 'text-muted' }}"
                            href="{{ route('registrar.student-profile') }}?student={{ base64_encode($_student->id) }}&view=enrollment">ENROLLMENT</a>
                        <a class="nav-link   {{ request()->input('view') == 'account' ? 'active' : 'text-muted' }}"
                            href="{{ route('registrar.student-profile') }}?student={{ base64_encode($_student->id) }}&view=account">ACCOUNT</a>
                        <a class="nav-link   {{ request()->input('view') == 'grades' ? 'active' : 'text-muted' }}"
                            href="{{ route('registrar.student-profile') }}?student={{ base64_encode($_student->id) }}&view=grades">CERTIFICATE
                            OF GRADE</a>

                        {{-- <a class="nav-link  " href="http://bma.edu.ph/bma/about-us">SETTING</a> --}}
                    </div>
                </nav>
                <div class="mt-4">
                    @if (request()->input('view') == 'profile' || !request()->input('view'))
                        @include('pages.administrator.student.profile-tab-content.student-information')
                    @endif
                    @if (request()->input('view') == 'enrollment')
                        @include('pages.administrator.student.profile-tab-content.enrollment-view')
                    @endif
                    @if (request()->input('view') == 'account')
                        @include('pages.administrator.student.profile-tab-content.account-view')
                    @endif
                    @if (request()->input('view') == 'grades')
                        @include('pages.administrator.student.profile-tab-content.grade-view')
                    @endif
                </div>
               
            @else
                <div class="card mb-2">
                    <div class="row no-gutters">
                        <div class="col-md-3">
                            <img src="http://bma.edu.ph/img/student-picture/midship-man.jpg" class="card-img"
                                alt="student-image">
                        </div>
                        <div class="col-md ps-0">
                            <div class="card-body p-3 me-2">
                                <label for="" class="fw-bolder text-primary h4">MIDSHIPMAN NAME</label>
                                <p class="mb-0">
                                    <small class="fw-bolder badge bg-secondary">
                                        COURSE
                                    </small> -
                                    <small class="badge bg-primary">
                                        ACADEMIC
                                    </small>
                                </p>
                                <p class="mb-0">
                                    <small class="fw-bolder badge bg-secondary">
                                        STUDENT NUMBER
                                    </small> -
                                    <small class="fw-bolder badge bg-secondary">
                                        YEAR LEVEL
                                    </small> -
                                    <small class="badge bg-primary">
                                        CURRICULUM
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>


    </div>

@endsection
@section('js')
    <script>
        $('.btn-modal-form').click(function(event) {
            Swal.fire({
                title: 'Course Subject',
                text: "Do you want to add?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                var form = $(this).data('form');
                if (result.isConfirmed) {

                    console.log(form)
                    document.getElementById(form).submit()
                }
            })
            event.preventDefault();
        })
    </script>
@endsection
