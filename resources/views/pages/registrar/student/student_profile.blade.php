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
            <div class="card">
                <div class="card-header m-0 p-0">
                    <div class="d-flex align-items-center m-0 p-0">
                        <img class="rounded-pill img-fluid avatar-120 me-3"
                            src="{{ asset($_student->profile_pic($_student->account)) }}" alt="">
                        <div class="media-support-info">
                            <h6 class="mb-0 fw-bolder">{{ strtoupper($_student->last_name . ', ' . $_student->first_name) }}
                            </h6>
                            <small class="badge bg-primary">
                                {{ $_student->enrollment_assessment ? $_student->enrollment_assessment->course->course_code : '-' }}

                            </small>
                            <p class="mb-0">
                                {{-- <span class="text-primary">
                                    <svg width="15" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z">
                                        </path>
                                    </svg>
                                </span> --}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="nav flex-column nav-pills text-center" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">

                        <a class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile"
                            role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</a>
                        <a class="nav-link" id="v-pills-account-tab" data-bs-toggle="pill" href="#v-pills-account"
                            role="tab" aria-controls="v-pills-account" aria-selected="true">Account</a>
                        <a class="nav-link" id="v-pills-enrollment-tab" data-bs-toggle="pill" href="#v-pills-enrollment"
                            role="tab" aria-controls="v-pills-Enrollment" aria-selected="false">Enrollment</a>
                        <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings"
                            role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md">
            <div class="tab-content mt-0" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel"
                    aria-labelledby="v-pills-profile-tab">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <a href="{{ route('registrar.student-application-view') }}?_student={{ base64_encode($_student->id) }}"
                                class="btn btn-primary btn-sm float-end">FORM RG-01</a>
                            <h5 class="mb-1"><b>PROFILE INFORMATION</b></h5>
                            <p class="text-sm">Student Information of the cadet's/ student's at Baliwag Maritime Academy</p>

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
                                            <label for="example-text-input" class="form-control-label">Civil
                                                Status</label>
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
                                            <label for="example-text-input" class="form-control-label">Hous no / Street /
                                                Bldg
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
                                            <label for="example-text-input"
                                                class="form-control-label">Municipality</label>
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
                                            <label for="example-text-input" class="form-control-label">Contact
                                                Number</label>

                                            <span
                                                class="form-control">{{ $_student->contact_number ?: 'Contact Number' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 mb-xl-0">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Email</label>
                                            <span
                                                class="form-control">{{ $_student->account ? $_student->account->personal_email : 'Personal Email' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-account" role="tabpanel" aria-labelledby="v-pills-account-tab">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <h5 class="mb-1"><b>ACCOUNT SETTING</b></h5>
                        </div>
                        <div class="card-body">

                            <label for="" class="fw-bolder text-muted h6">ACCOUNT LIST</label>
                            <button class="btn btn-sm btn-primary float-end" data-bs-toggle="modal"
                                data-bs-target=".model-add-account">ADD ACCOUNT</button>
                            <div class="account-content">
                                @if ($_student->account)
                                    @foreach ($_student->account_list as $item)
                                        <div class="account-list row">
                                            <div class="col-md-3">
                                                <small class="fw-bolder">
                                                    ACCOUNT STAT.
                                                </small> <br>
                                                @if ($item->is_actived == 1)
                                                    <label for="" class="text-primary fw-bolder">
                                                        ACTIVE
                                                    </label>
                                                @else
                                                    <label for="" class="text-danger fw-bolder">
                                                        DEACTIVE
                                                    </label>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <small class="fw-bolder">
                                                    STUDENT NO.
                                                </small> <br>
                                                <label for="" class="text-primary fw-bolder">
                                                    {{ $item->student_number }}
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="fw-bolder">
                                                    CAMPUS EMAIL
                                                </small> <br>
                                                <label for="" class="text-primary fw-bolder">
                                                    {{ $item->email }}
                                                </label>
                                            </div>
                                            <div class="col-md">
                                                <small class="fw-bolder">
                                                    PERSONAL EMAIL
                                                </small> <br>
                                                <label for="" class="text-primary fw-bolder">
                                                    {{ $item->personal_email }}
                                                </label>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                    {{-- {{ $_student->account_list }} --}}
                                @else
                                    <div class="account-list row">
                                        <label for="" class="fw-bolder text-muted">NO STUDENT ACCOUNT</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-enrollment" role="tabpanel"
                    aria-labelledby="v-pills-enrollment-tab">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <h5 class="mb-1"><b>ENROLLMENT</b></h5>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <h5 class="mb-1"><b>ENROLLMENT HISTORY</b></h5>
                        </div>
                        <div class="card-body">
                            @if (count($_student->enrollment_history))
                                @foreach ($_student->enrollment_history as $item)
                                    <div class="account-list">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <small class="fw-bolder">
                                                    SCHOOL ACADEMIC
                                                </small> <br>
                                                <label for="" class="text-primary fw-bolder">
                                                    {{ strtoupper($item->academic->semester . ' - ' . $item->academic->school_year) }}
                                                </label>
                                            </div>
                                            <div class="col-md">
                                                <small class="fw-bolder">
                                                    ENROLLMENT DATE
                                                </small> <br>
                                                <label for="" class="badge bg-secondary">
                                                    {{ $item->payment_assessments ? $item->payment_assessments->created_at->format('F d,Y') : '' }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <small class="fw-bolder">
                                                    COURSE / STRAND
                                                </small> <br>
                                                <label for="" class="badge bg-primary">
                                                    {{ $item->course->course_name }}
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="fw-bolder">
                                                    YEAR LEVEL
                                                </small> <br>
                                                <label for="" class="badge bg-primary">
                                                    {{ strtoupper(Auth::user()->staff->convert_year_level($item->year_level)) }}
                                                </label>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="fw-bolder">
                                                    CURRICULUM
                                                </small> <br>
                                                <label for="" class="badge bg-primary">
                                                    {{ strtoupper($item->curriculum->curriculum_name) }}
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <small class="fw-bolder">
                                                    COR
                                                </small> <br>
                                                <a href="{{ route('registrar.student-information-report') }}?_assessment={{ base64_encode($item->id) }}"
                                                    class="badge bg-info" target="_blank">PRINT</a>
                                            </div>
                                        </div>

                                    </div>

                                    <hr>
                                @endforeach
                            @else
                                <div class="enrollment-list row">
                                    <label for="" class="fw-bolder text-muted">NO ENROLLMENT DETIALS</label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                        industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                        and scrambled it to make a type specimen book. It has survived not only five centuries, but also the
                        leap into electronic typesetting, remaining essentially unchanged.</p>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade model-add-account" tabindex="-1" role="dialog" aria-labelledby="model-add-accountTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal-title fw-bolder text-primary" id="model-add-accountTitle">ADD ACCOUNT
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" action="{{ route('admin.store-student-account') }}" method="POST"
                        id="modal-form-add">
                        @csrf
                        <input type="hidden" name="student" value="{{ base64_encode($_student->id) }}">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md">
                                    <small class="fw-bolder">
                                        STUDENT NUMBER
                                    </small> <br>
                                    <input type="text" class="form-control" name="student_number">
                                </div>
                                <div class="col-md">
                                    <small class="fw-bolder">
                                        PERSONAL EMAIL
                                    </small> <br>
                                    <input type="text" class="form-control" name="personal_email">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm btn-modal-form"
                        data-form="modal-form-add">ADD</button>
                </div>
            </div>
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
