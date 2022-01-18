@extends('layouts.app-main')
@php
$_title = 'Employee Profile';
@endphp
@section('page-title', $_title)
@section('content-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item active" aria-current="page">
        <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>{{ $_title }}
    </li>
@endsection
@section('page-content')


    <div class="card mb-0 iq-content rounded-bottom">
        <div class="d-flex flex-wrap align-items-center justify-content-between mx-3 my-3">
            <div class="d-flex flex-wrap align-items-center">
                <div class="profile-img position-relative me-3 mb-3 mb-lg-0">
                    <img src="{{ asset(Auth::user()->staff->profile_pic(Auth::user()->staff)) }}" alt="User-Profile"
                        class="img-fluid avatar avatar-90 rounded-circle">
                </div>
                <div class="d-flex align-items-center mb-3 mb-sm-0">
                    <div>
                        <h4 class="me-2 text-primary">
                            {{ Auth::user()->staff->last_name . ', ' . Auth::user()->staff->first_name }}</h4>
                        <span>
                            <small class="mb-0 text-dark">
                                {{ Auth::user()->email }} |
                                {{ Auth::user()->staff->job_description }} |
                                {{ Auth::user()->staff->department }}
                            </small>
                        </span>
                    </div>
                </div>
            </div>
            {{-- <ul class="d-flex mb-0 text-center ">
                <li class="badge bg-primary py-2 me-2">
                    <p class="mb-2 mt-1">142</p>
                    <small class="mb-1 fw-normal">Reviews</small>
                </li>
                <li class="badge bg-primary py-2 me-2">
                    <p class="mb-2 mt-1">201</p>
                    <small class="mb-1 fw-normal">Photos</small>
                </li>
                <li class="badge bg-primary py-2 me-2">
                    <p class="mb-2 mt-1">3.1k</p>
                    <small class="mb-1 fw-normal">Followers</small>
                </li>
            </ul> --}}
        </div>
    </div>

    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h5 class="mb-1"><b>EMPLOYEE INFORMATION</b></h5>
                <p class="text-sm">...</p>
            </div>
            <div class="card-body p-3">
                {{-- <div class="form-view">
                    <h6 class="mb-1"><b>FULL NAME</b></h6>
                    <div class="row">
                        <div class="col-xl col-md-6 ">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Last name</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->last_name) }}</span>
                            </div>
                        </div>
                        <div class="col-xl col-md-6 ">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">First name</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->first_name) }}</span>
                            </div>
                        </div>
                        <div class="col-xl col-md-6 ">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Middle name</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->middle_name) }}</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6 ">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Extension</label>
                                <span
                                    class="form-control">{{ Auth::user()->student->extention_name ? ucwords(Auth::user()->student->extention_name) : 'none' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-2 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Gender</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->sex) }}</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Birthday</label>
                                <span class="form-control">{{ Auth::user()->student->birthday }}</span>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Birth Place</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->birth_place) }}</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Civil Status</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->civil_status) }}</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Nationality</label>
                                <span class="form-control">{{ Auth::user()->student->nationality }}</span>
                            </div>
                        </div>
                    </div>


                    <h6 class="mb-1"><b>ADDRESS</b></h6>
                    <div class="row">
                        <div class="col-xl-5 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Hous no / Street / Bldg
                                    no</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->street) }}</span>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Barangay</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->barangay) }}</span>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Zip Code</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->zip_code) }}</span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Municipality</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->municipality) }}</span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Province</label>
                                <span class="form-control">{{ ucwords(Auth::user()->student->province) }}</span>
                            </div>
                        </div>
                    </div>
                    <h6 class="mb-1"><b>CONTACT DETIALS</b></h6>
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Contact Number</label>

                                <span class="form-control">{{ Auth::user()->contact_number ?: 'Missing Value' }}</span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-xl-0">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Email</label>
                                <span class="form-control">{{ Auth::user()->personal_email }}</span>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h5 class="mb-1"><b>Change Password</b></h5>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('employee.change-password') }}" method="post">
                    @csrf
                    {{-- <div class="form-group">
                        <label class="form-label" for="old-password">Old Password:</label>
                        <input type="password" class="form-control" name="old_password">
                    </div> --}}
                    <div class="form-group">
                        <label class="form-label" for="new-password">New Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="new-password">Confirm New Password:</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    @error('password')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>

            </div>
        </div>
    </div>
@endsection
