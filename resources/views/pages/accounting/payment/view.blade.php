@extends('layouts.app-main')
@section('page-title', 'Payment Transaction')
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>Payment Transaction
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-4 col-lg-2">

                        <img src="{{ $_student? $_student->profile_pic($_student->account): 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="avatar-130 rounded" alt="#">
                    </div>
                    <div class="col-md col-lg">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name) : 'MIDSHIPMAN NAME' }}</b>
                            </h4>
                            <p class="card-text">
                                <span>STUDENT NUMBER: <b>
                                        {{ $_student ? $_student->account->student_number : '-' }}</b></span>
                            </p>

                        </div>
                    </div>
                </div>
                <div class="row p-3">
                    {{-- <span class="text-primary"><b>| ENROLLMENT DETAILS</b></span>

                    <div class="row">
                        <div class="col-md">
                            <label for="" class="form-label"><small><b>COURSE / STRAND</b></small>:</label>
                            <label for=""
                                class="text-primary"><b>{{ $_assessment ? $_assessment->course->course_name : '-' }}</b></label>
                        </div>
                        <div class="col-md-4">
                            <label for="" class="form-label"><small><b>YEAR LEVEL</b></small>:</label>
                            <label for=""
                                class="text-primary"><b>{{ $_assessment? ($_assessment->course->id != 3? $_assessment->year_level . ' CLASS': 'GRADE ' . $_assessment->year_level): '-' }}</b></label>

                        </div>
                        <div class="col-md-12">
                            <label for="" class="form-label"><small><b>ACADEMIC YEAR</b></small>: </label>
                            <label class="text-primary">
                                <b>{{ $_assessment ? $_assessment->academic->semester . ' | ' . $_assessment->academic->school_year : '-' }}
                                </b>
                            </label>
                        </div>
                    </div> --}}
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <form action="" method="get">
                @if (request()->input('search_name'))
                    <input type="hidden" name="search_name" value="{{ request()->input('search_name') }}">
                @endif
                <div class="form-group search-input">
                    <input type="search" class="form-control" placeholder="Search..." name="_students">
                </div>
            </form>

            @if ($_students)
                @foreach ($_students as $item)
                    <div class="card border-bottom border-4 border-0 border-primary">
                        <a href="?_midshipman={{ base64_encode($item->id) }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="text-primary"><b>{{ strtoupper($item->last_name . ', ' . $item->first_name) }}</b></span>
                                    </div>
                                    <div>
                                        <span>{{ $item->account->student_number }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach

            @else
                <div class="card border-bottom border-4 border-0 border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span>NO DATA</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
