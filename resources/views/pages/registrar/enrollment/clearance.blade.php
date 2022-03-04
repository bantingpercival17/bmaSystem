@extends('layouts.app-main')
@php
$_title = 'Enrollment';
@endphp
@section('page-title', $_title)
@section('content-title', $_title)
@section('beardcrumb-content')
    <li class="breadcrumb-item ">
        <a href="{{ route('registrar.enrollment') }}">
            <svg width="14" height="14" class="me-2" viewBox="0 0 22 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8.15722 19.7714V16.7047C8.1572 15.9246 8.79312 15.2908 9.58101 15.2856H12.4671C13.2587 15.2856 13.9005 15.9209 13.9005 16.7047V16.7047V19.7809C13.9003 20.4432 14.4343 20.9845 15.103 21H17.0271C18.9451 21 20.5 19.4607 20.5 17.5618V17.5618V8.83784C20.4898 8.09083 20.1355 7.38935 19.538 6.93303L12.9577 1.6853C11.8049 0.771566 10.1662 0.771566 9.01342 1.6853L2.46203 6.94256C1.86226 7.39702 1.50739 8.09967 1.5 8.84736V17.5618C1.5 19.4607 3.05488 21 4.97291 21H6.89696C7.58235 21 8.13797 20.4499 8.13797 19.7714V19.7714"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>{{ $_title }}
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Student Clearance</li>
@endsection


@section('page-content')


    <div class="card mt-5 iq-content rounded-bottom ">
        <div class="d-flex flex-wrap align-items-center justify-content-between mx-3 my-3">
            <div class="d-flex flex-wrap align-items-center">
                <div class="profile-img position-relative me-3 mb-3 mb-lg-0">
                    <img src="{{ asset($_student->profile_pic($_student->account)) }}" alt="User-Profile"
                        class="img-fluid avatar avatar-90 rounded-circle">
                </div>
                <div class="d-flex align-items-center mb-3 mb-sm-0">
                    <div>
                        <h4 class="me-2 text-primary">
                            {{ $_student->last_name . ', ' . $_student->first_name }}</h4>
                        <span><svg width="19" height="19" class="me-2" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21 10.8421C21 16.9172 12 23 12 23C12 23 3 16.9172 3 10.8421C3 4.76697 7.02944 1 12 1C16.9706 1 21 4.76697 21 10.8421Z"
                                    stroke="#07143B" stroke-width="1.5" />
                                <circle cx="12" cy="9" r="3" stroke="#07143B" stroke-width="1.5" />
                            </svg><small
                                class="mb-0 text-dark">{{ ucwords($_student->municipality . ', ' . $_student->province) }}</small></span>
                    </div>
                </div>
            </div>
            <div class="d-flex mb-0 text-center ">

            </div>
        </div>
    </div>

    <div class="nav-scroller text-center">
        <ul class="nav nav-underline bg-soft-primary pb-0" id="myTab-two" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="home-tab-two" data-bs-toggle="tab" href="#home-two" role="tab"
                    aria-controls="home" aria-selected="true">Student Infromation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link  active" id="profile-tab-two" data-bs-toggle="tab" href="#profile-two" role="tab"
                    aria-controls="profile" aria-selected="false">Clearance</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab-two" data-bs-toggle="tab" href="#contact-two" role="tab"
                    aria-controls="contact" aria-selected="false">Enrollment History</a>
            </li>
        </ul>
    </div>
    <div class="tab-content mt-5" id="myTabContent-1">
        <div class="tab-pane fade " id="home-two" role="tabpanel" aria-labelledby="home-tab-two">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-0 p-3">
                    <div class="card-title">
                        <h5 class="mb-1"><b>PROFILE INFORMATION</b></h5>
                        <p class="text-sm">Student Information of the cadet's/ student's at Baliwag Maritime Academy
                        </p>
                    </div>

                    <div class="tool-card">
                        <a href="{{ route('registrar.student-information-report') }}?_assessment={{ base64_encode($_student->enrollment_assessment->id) }}"
                            class="btn btn-sm btn-primary">
                            {{ $_student->enrollment_assessment->course_id == 3 ? 'FORM RG-04' : 'FORM SHS RG-03' }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">

                    <div class="form-view">
                        <h6 class="mb-1"><b>FULL NAME</b></h6>
                        <div class="row">
                            <div class="col-xl col-md-6 ">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Last name</label>
                                    <span class="form-control">{{ ucwords($_student->last_name) }}</span>
                                </div>
                            </div>
                            <div class="col-xl col-md-6 ">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">First name</label>
                                    <span class="form-control">{{ ucwords($_student->first_name) }}</span>
                                </div>
                            </div>
                            <div class="col-xl col-md-6 ">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Middle name</label>
                                    <span class="form-control">{{ ucwords($_student->middle_name) }}</span>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-6 ">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Extension</label>
                                    <span
                                        class="form-control">{{ $_student->extention_name ? ucwords($_student->extention_name) : 'none' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-2 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Gender</label>
                                    <span class="form-control">{{ ucwords($_student->sex) }}</span>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Birthday</label>
                                    <span class="form-control">{{ $_student->birthday }}</span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Birth Place</label>
                                    <span class="form-control">{{ ucwords($_student->birth_place) }}</span>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Civil Status</label>
                                    <span class="form-control">{{ ucwords($_student->civil_status) }}</span>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Nationality</label>
                                    <span class="form-control">{{ $_student->nationality }}</span>
                                </div>
                            </div>
                        </div>


                        <h6 class="mb-1"><b>ADDRESS</b></h6>
                        <div class="row">
                            <div class="col-xl-5 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Hous no / Street / Bldg
                                        no</label>
                                    <span class="form-control">{{ ucwords($_student->street) }}</span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Barangay</label>
                                    <span class="form-control">{{ ucwords($_student->barangay) }}</span>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Zip Code</label>
                                    <span class="form-control">{{ ucwords($_student->zip_code) }}</span>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Municipality</label>
                                    <span class="form-control">{{ ucwords($_student->municipality) }}</span>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Province</label>
                                    <span class="form-control">{{ ucwords($_student->province) }}</span>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-1"><b>CONTACT DETIALS</b></h6>
                        <div class="row">
                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Contact Number</label>

                                    <span
                                        class="form-control">{{ $_student->account->contact_number ?: 'Missing Value' }}</span>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Email</label>
                                    <span class="form-control">{{ $_student->account->personal_email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="tab-pane fade show active" id="profile-two" role="tabpanel" aria-labelledby="profile-tab-two">
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h5 class="mb-1"><b>E-Clearance</b></h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><b>Subject</b></th>
                                        <th><b>Status</b></th>
                                        <th><b>Comment</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($_subject_class) > 0)
                                        @foreach ($_subject_class as $_subject)
                                            @if ($_subject->curriculum_subject->subject->subject_code == 'BRDGE')
                                                @if ($_student->enrollment_assessment->bridging_program != 'without')
                                                    <tr>
                                                        <td>
                                                            <b class="text-primary">
                                                                {{ $_subject->curriculum_subject->subject->subject_code }}
                                                            </b>
                                                            <br>
                                                            <small
                                                                class="text-muted"><b>{{ strtoupper($_subject->staff->user->name) }}</b></small>
                                                        </td>
                                                        <td>
                                                            @if ($_subject->e_clearance)
                                                                @if ($_subject->e_clearance->is_approved == 1)
                                                                    <span class="text-primary"><b>Cleared</b></span>
                                                                @else
                                                                    <span class="text-warning"><b>Not
                                                                            Clear</b></span><br>
                                                                @endif
                                                            @else
                                                                <span class="text-danger">-</span>
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @if ($_subject->e_clearance)
                                                                @if ($_subject->e_clearance->is_approved != 1)
                                                                    <span class="text-muted">
                                                                        <b>{{ $_subject->e_clearance->comment }}</b></span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td>
                                                        <b class="text-primary">
                                                            {{ $_subject->curriculum_subject->subject->subject_code }}
                                                        </b>
                                                        <br>
                                                        <small
                                                            class="text-muted"><b>{{ strtoupper($_subject->staff->user->name) }}</b></small>
                                                    </td>
                                                    <td>
                                                        @if ($_subject->e_clearance)
                                                            @if ($_subject->e_clearance->is_approved == 1)
                                                                <span class="text-primary"><b>Cleared</b></span>
                                                            @else
                                                                <span class="text-warning"><b>Not Clear</b></span><br>
                                                            @endif
                                                        @else
                                                            <span class="text-danger">-</span>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if ($_subject->e_clearance)
                                                            @if ($_subject->e_clearance->is_approved != 1)
                                                                <span class="text-muted">
                                                                    <b>{{ $_subject->e_clearance->comment }}</b></span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <th colspan="4" class="text-center text-muted"> Empty Subject</th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <text-primary class="h5">Non-Academic</text-primary>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><b>Personel</b></th>
                                        <th><b>Status</b></th>
                                        <th><b>Comment</b></th>
                                        <th><b>Contact</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $_department = ['Department Head', 'Laboratory', 'Dean', 'Library', 'Exo', 'Accounting', 'Registrar', 'ICT'];
                                    @endphp
                                    @if (count($_department) > 0)
                                        @foreach ($_department as $_data)
                                            <tr>
                                                <th>
                                                    <b class="text-primary">
                                                        {{ $_data }}
                                                    </b>
                                                <td>
                                                    @if ($_student->non_academic_clearance_for_enrollment($_data))
                                                        @if ($_student->non_academic_clearance_for_enrollment($_data)->is_approved == 1)
                                                            <span class="text-primary"><b>Cleared</b></span>
                                                        @else
                                                            <span class="text-warning"><b>Not Clear</b></span><br>
                                                        @endif
                                                    @else
                                                        <span class="text-danger">-</span>
                                                    @endif
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th colspan="4" class="text-center text-muted"> Non Academic Clearance</th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="contact-two" role="tabpanel" aria-labelledby="contact-tab-two">
            @foreach ($_student->enrollment_history as $item)
            @endforeach
        </div>
    </div>

@endsection
