@extends('layouts.app-main')
@php
    $_title = 'Employee';
@endphp
@section('page-title', 'Employee')
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
        <div class="col-lg-9 col-md-12">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-3 col-xs-12 text-center">
                        @php
                            if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg'))) {
                                $_image = strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg';
                            } else {
                                $_image = 'avatar.png';
                            }
                        @endphp
                        <img class="img-circle elevation-2" src="{{ asset('/assets/img/staff/' . $_image) }}"
                            alt="User Avatar" height="120px">

                    </div>
                    <div class="col-md-9 col-xs-12">
                        <h5><b class="text-muted">EMPLOYEE'S INFORMATION</b></h5>
                        <h4 class="text-info">
                            <b>{{ strtoupper(trim($_staff->first_name . ' ' . $_staff->last_name)) }}</b>
                        </h4>
                        <div class="row">
                            <div class="col-md">
                                <small class="text-muted"><b>JOB DESCRIPTION</b></small><br>
                                <span class="h5 text-info"><b>{{ strtoupper($_staff->job_description) }}</b></span>
                            </div>
                            <div class="col-md">
                                <small class="text-muted"><b>DEPARTMENT</b></small><br>
                                <span class="h5 text-info"><b>{{ strtoupper($_staff->department) }}</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-3 col-md-4">
            <div class="card">
                <div class="card-header">
                    <label for="" class="text-muted"><b>ADD ROLES</b></label>
                    <div class="card-tools">
                        <form action="/administrator/accounts/role" method="post">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="hidden" name="id" value="{{ $_staff->user->id }}">
                                <div class="custom-file">
                                    <select name="_role" id="" class="form-control">
                                        @foreach ($_roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-info btn-sm" type="submit"><i class="fas fa-plus"></i></button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
                <div class="card-body">
                    @foreach ($_staff->user->roles as $item)
                        <span class="badge badge-info"> {{ $item->display_name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        @php
                            if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg'))) {
                                $_image = strtolower(str_replace(' ', '_', $_staff->user->name)) . '.jpg';
                            } else {
                                $_image = 'avatar.png';
                            }
                        @endphp
                        <img class="card-img" src="{{ asset('/assets/img/staff/' . $_image) }}" alt="User Avatar">

                    </div>
                    <div class="col-md ps-0">
                        <div class="card-body p-3 me-2">
                            <h5><b class="text-muted">EMPLOYEE'S INFORMATION</b></h5>
                            <h4 class="text-info">
                                <b>{{ strtoupper(trim($_staff->first_name . ' ' . $_staff->last_name)) }}</b>
                            </h4>
                            <div class="row">
                                <div class="col-md">
                                    <small class="text-muted"><b>JOB DESCRIPTION</b></small><br>
                                    <span class="h5 text-info"><b>{{ strtoupper($_staff->job_description) }}</b></span>
                                </div>
                                <div class="col-md">
                                    <small class="text-muted"><b>DEPARTMENT</b></small><br>
                                    <span class="h5 text-info"><b>{{ strtoupper($_staff->department) }}</b></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card-body p-3 me-2">
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
                        </div> --}}
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
