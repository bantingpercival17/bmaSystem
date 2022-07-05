@extends('layouts.app-main')
@php
$_title = 'Profile View';
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
    <li class="breadcrumb-item">
        <a href="{{ route('applicant-lists') }}?_course={{ base64_encode($_account->course_id) }}">Applicant List</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_title }}
    </li>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="row no-gutters">
                    <div class="col-md-4 col-lg-2">

                        {{-- <img src="http://bma.edu.ph/img/student-picture/midship-man.jpg" class="avatar-130 rounded"
                            alt="applicant-profile"> --}}
                        @if ($_account->image)
                            <img src="{{ json_decode($_account->image->file_links)[0] }}" class="avatar-130 rounded"
                                alt="applicant-profile">
                        @else
                        @endif

                    </div>
                    <div class="col-md col-lg">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $_account->applicant ? strtoupper($_account->applicant->last_name . ', ' . $_account->applicant->first_name) : 'APPLICANT NAME' }}</b>
                            </h4>
                            <p class="card-text">
                                <span>
                                    <b>
                                        {{ $_account->applicant ? $_account->applicant_number : 'APPLICANT NO.' }}
                                        |
                                        {{ $_account->applicant ? $_account->course->course_name : 'COURSE' }}
                                    </b>
                                </span>
                            </p>
                            @if ($_account->is_alumnia)
                                <span class="badge bg-primary float-end">
                                    BMA ALUMINA
                                </span>
                            @else
                                @if ($_account->senior_high_school())
                                    <button class="btn btn-outline-primary btn-sm float-end rounded-pill" id="btn-alumnia"
                                        data-id="{{ base64_encode($_account->id) }}"> TAG AS ALUMNIA</button>
                                @endif

                            @endif

                        </div>
                    </div>
                </div>
            </div>
            @if ($_account->applicant)
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between  pb-0 p-3">
                        <div class="header-title">
                            <h5 class="mb-1"><b>APPLICANT DETAILS</b></h5>
                        </div>
                        <div>
                            <a href="{{ route('applicant-form') }}?_applicant={{ base64_encode($_account->id) }}"
                                class="btn btn-sm btn-info mb-3 text-white">FORM RG-01</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills nav-pill mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a href="{{ request()->url() . '?_applicant=' . request()->input('_applicant') }}"
                                    class="nav-link {{ request()->input('_fill') ?: 'active' }}">Applicant Profile</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ request()->url() . '?_applicant=' . request()->input('_applicant') }}&_fill=document"
                                    class="nav-link {{ request()->input('_fill') != 'document' ? '' : 'active' }}">Document
                                    Cheking</a>
                            </li>

                            @if ($_account->applicant_examination)
                                @if ($_account->applicant_examination->is_finish == 1)
                                    <li class="nav-item">
                                        <a href="{{ request()->url() . '?_applicant=' . request()->input('_applicant') }}&_fill=entrance-examination"
                                            class="nav-link {{ request()->input('_fill') != 'entrance-examination' ? '' : 'active' }}">Entrance
                                            Examination</a>
                                    </li>
                                @endif
                            @endif
                            @if ($_account->similar_account())
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="modal" data-bs-target=".document-view-profile"
                                        data-bs-toggle="tooltip" title="" data-bs-original-title="View Image">

                                        <svg width="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>
                                        Found Similar Information
                                    </a>
                                </li>
                            @endif

                        </ul>
                        @include('pages.general-view.applicants.profile_tab')
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <span class="fw-bolder">
                        {{ strtoupper($_account->course->course_name) }}
                    </span>

                </div>
                <div>
                    <div class="justify-content-between">
                        No. Result:
                        <span class="text-muted fw-bolder"> {{ count($_applicants) }}</span>
                    </div>
                </div>
            </div>
            <div class="list-view">
                @if (count($_applicants) > 0)
                    @foreach ($_applicants as $item)
                        <div class="card mb-2">
                            <a href="{{ route('applicant-profile') }}?_student={{ base64_encode($item->id) }}">
                                <div class="row no-gutters">
                                    <div class="col-md col-lg">
                                        <div class="card-body">
                                            <span class="card-title text-primary h5">
                                                <b>{{ $item->applicant ? strtoupper($item->applicant->last_name . ', ' . $item->applicant->first_name) : 'APPLICANT NAME' }}</b>
                                            </span> <br>
                                            <small class="fw-bolder">
                                                {{ $item->applicant ? $item->applicant_number : 'APPLICANT NO.' }} |
                                                {{ $item->applicant ? $item->course->course_name : 'COURSE' }}
                                            </small>

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="card mb-2">
                        <a>
                            <div class="row no-gutters">
                                <div class="col-md col-lg">
                                    <div class="card-body">
                                        <span class="card-title text-primary h5">
                                            <b>No Data Result</b>
                                        </span> <br>

                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
    <div class="modal fade document-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Document Review</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="btn-group " role="group" aria-label="Basic example">

                    </div>
                    <iframe class="iframe-container form-view iframe-placeholder" width="100%" height="600px">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade document-view-profile" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bolder" id="exampleModalLabel1">Similar Information</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row">
                            <div class="col-md">
                                <div class="card mb-2">
                                    <div class="row no-gutters">
                                        <div class="col-md-4 col-lg-2">
                                            @if ($_account->image)
                                                <img src="{{ json_decode($_account->image->file_links)[0] }}"
                                                    class="avatar-130 rounded" alt="applicant-profile">
                                            @else
                                            @endif

                                        </div>
                                        <div class="col-md col-lg">
                                            <div class="card-body">
                                                <h4 class="card-title text-primary">
                                                    <b>{{ $_account->applicant ? strtoupper($_account->applicant->last_name . ', ' . $_account->applicant->first_name) : 'APPLICANT NAME' }}</b>
                                                </h4>
                                                <p class="card-text">
                                                    <span>
                                                        <b>
                                                            {{ $_account->applicant ? $_account->applicant_number : 'APPLICANT NO.' }}
                                                            |
                                                            {{ $_account->applicant ? $_account->course->course_name : 'COURSE' }}
                                                        </b>
                                                    </span>
                                                    <a href="{{ route('applicant-removed') }}?_applicant={{ base64_encode($_account->id) }}"
                                                        class="btn btn-outline-danger btn-sm ">REMOVE
                                                    </a>
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="form-view">
                                        <div class="form-group">
                                            <small class="fw-bolder mb-1">FULL NAME</small>
                                            <p class="fw-bolder text-primary">
                                                {{ strtoupper($_account->applicant->last_name . ', ' . $_account->applicant->first_name . ' ' . $_account->applicant->middle_name . ($_account->applicant->extention_name ? ucwords($_account->applicant->extention_name) : '')) }}
                                            </p>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md">
                                                <small class="fw-bolder mb-1">GENDER</small>
                                                <p class="fw-bolder text-primary"> {{ ucwords('male') }}</p>
                                            </div>
                                            <div class="form-group col-md">
                                                <small class="fw-bolder mb-1">BIRTHDAY</small>
                                                <p class="fw-bolder text-primary">{{ $_account->applicant->birthday }}
                                                </p>
                                            </div>
                                            <div class="form-group col-md">
                                                <small class="fw-bolder mb-1">CIVIL STATUS</small>
                                                <p class="fw-bolder text-primary">
                                                    {{ ucwords($_account->applicant->civil_status) }}</p>
                                            </div>
                                            <div class="form-group col-md">
                                                <small class="fw-bolder mb-1">NATIONALITY</small>
                                                <p class="fw-bolder text-primary">
                                                    {{ ucwords($_account->applicant->nationality) }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <small class="fw-bolder mb-1">BIRTH PLACE</small>
                                            <p class="fw-bolder text-primary">
                                                {{ strtoupper($_account->applicant->birth_place) }}</p>
                                        </div>
                                        <div class="form-group">
                                            <small class="fw-bolder mb-1">ADDRESS</small>
                                            <p class="fw-bolder text-primary">
                                                {{ strtoupper($_account->applicant->street . ' ' . $_account->applicant->barangay . ' ' . $_account->applicant->municipality . ', ' . $_account->applicant->province . ' ' . $_account->applicant->zip_code) }}
                                            </p>
                                        </div>

                                        <h6 class="mb-1"><b>CONTACT DETIALS</b></h6>
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="fw-bolder">CONTACT
                                                        NUMBER</small>

                                                    <span
                                                        class="fw-bolder text-primary">{{ $_account->contact_number ?: '' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-6 mb-xl-0">
                                                <div class="form-group">
                                                    <small for="example-text-input" class="fw-bolder">EMAIL</small>
                                                    <span class="fw-bolder text-primary">{{ $_account->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="mb-1"><b>PARENTS INFORMATION</b></h6>
                                        <div class="form-group">
                                            <small class="fw-bolder mb-1">FATHER NAME</small>
                                            <p class="fw-bolder text-primary">
                                                {{ strtoupper($_account->applicant->father_last_name . ', ' . $_account->applicant->father_first_name . ' ' . $_account->applicant->father_middle_name) }}
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <small class="fw-bolder mb-1">MOTHER MAIDEN NAME</small>
                                            <p class="fw-bolder text-primary">
                                                {{ strtoupper($_account->applicant->mother_last_name . ', ' . $_account->applicant->mother_first_name . ' ' . $_account->applicant->mother_middle_name) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                @if ($_similar_account)
                                    <div class="card mb-2">
                                        <div class="row no-gutters">
                                            <div class="col-md-4 col-lg-2">
                                                @if ($_similar_account)
                                                    @if ($_similar_account->image)
                                                        <img src="{{ json_decode($_similar_account->image->file_links)[0] }}"
                                                            class="avatar-130 rounded" alt="applicant-profile">
                                                    @else
                                                    @endif
                                                @endif


                                            </div>
                                            <div class="col-md col-lg">
                                                <div class="card-body">
                                                    <h4 class="card-title text-primary">
                                                        <b>{{ $_similar_account->applicant ? strtoupper($_similar_account->applicant->last_name . ', ' . $_similar_account->applicant->first_name) : 'APPLICANT NAME' }}</b>
                                                    </h4>
                                                    <p class="card-text">
                                                        <span>
                                                            <b>
                                                                {{ $_similar_account->applicant ? $_similar_account->applicant_number : 'APPLICANT NO.' }}
                                                                |
                                                                {{ $_similar_account->applicant ? $_similar_account->course->course_name : 'COURSE' }}
                                                            </b>
                                                        </span>
                                                        <a href="{{ route('applicant-removed') }}?_applicant={{ base64_encode($_similar_account->id) }}"
                                                            class="btn btn-outline-danger btn-sm ">REMOVE
                                                        </a>
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="form-view">
                                            <div class="form-group">
                                                <small class="fw-bolder mb-1">FULL NAME</small>
                                                <p class="fw-bolder text-primary">
                                                    {{ strtoupper($_similar_account->applicant->last_name . ', ' . $_similar_account->applicant->first_name . ' ' . $_similar_account->applicant->middle_name . ($_similar_account->applicant->extention_name ? ucwords($_similar_account->applicant->extention_name) : '')) }}
                                                </p>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md">
                                                    <small class="fw-bolder mb-1">GENDER</small>
                                                    <p class="fw-bolder text-primary"> {{ ucwords('male') }}</p>
                                                </div>
                                                <div class="form-group col-md">
                                                    <small class="fw-bolder mb-1">BIRTHDAY</small>
                                                    <p class="fw-bolder text-primary">
                                                        {{ $_similar_account->applicant->birthday }}
                                                    </p>
                                                </div>
                                                <div class="form-group col-md">
                                                    <small class="fw-bolder mb-1">CIVIL STATUS</small>
                                                    <p class="fw-bolder text-primary">
                                                        {{ ucwords($_similar_account->applicant->civil_status) }}</p>
                                                </div>
                                                <div class="form-group col-md">
                                                    <small class="fw-bolder mb-1">NATIONALITY</small>
                                                    <p class="fw-bolder text-primary">
                                                        {{ ucwords($_similar_account->applicant->nationality) }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <small class="fw-bolder mb-1">BIRTH PLACE</small>
                                                <p class="fw-bolder text-primary">
                                                    {{ strtoupper($_similar_account->applicant->birth_place) }}</p>
                                            </div>
                                            <div class="form-group">
                                                <small class="fw-bolder mb-1">ADDRESS</small>
                                                <p class="fw-bolder text-primary">
                                                    {{ strtoupper($_similar_account->applicant->street . ' ' . $_similar_account->applicant->barangay . ' ' . $_similar_account->applicant->municipality . ', ' . $_similar_account->applicant->province . ' ' . $_similar_account->applicant->zip_code) }}
                                                </p>
                                            </div>

                                            <h6 class="mb-1"><b>CONTACT DETIALS</b></h6>
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6 mb-xl-0">
                                                    <div class="form-group">
                                                        <small for="example-text-input" class="fw-bolder">CONTACT
                                                            NUMBER</small>

                                                        <span
                                                            class="fw-bolder text-primary">{{ $_similar_account->contact_number ?: '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-md-6 mb-xl-0">
                                                    <div class="form-group">
                                                        <small for="example-text-input" class="fw-bolder">EMAIL</small>
                                                        <span
                                                            class="fw-bolder text-primary">{{ $_similar_account->email }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <h6 class="mb-1"><b>PARENTS INFORMATION</b></h6>
                                            <div class="form-group">
                                                <small class="fw-bolder mb-1">FATHER NAME</small>
                                                <p class="fw-bolder text-primary">
                                                    {{ strtoupper($_similar_account->applicant->father_last_name . ', ' . $_similar_account->applicant->father_first_name . ' ' . $_similar_account->applicant->father_middle_name) }}
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <small class="fw-bolder mb-1">MOTHER MAIDEN NAME</small>
                                                <p class="fw-bolder text-primary">
                                                    {{ strtoupper($_similar_account->applicant->mother_last_name . ', ' . $_similar_account->applicant->mother_first_name . ' ' . $_similar_account->applicant->mother_middle_name) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@section('js')
    <script>
        $(document).on('click', '#btn-alumnia', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "This is BMA Alumnia!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('applicant.bma-alumnia') }}" + "?_applicant=" + $(this).data(
                        'id')
                    $.get(url, function(result) {
                        if (result.respond.respond == 202) {
                            Swal.fire(
                                'Complete',
                                result,
                                'success'
                            )
                            location.reload();
                        }
                        console.log(result);

                    })

                }
            })
        })
    </script>
@endsection
@endsection
