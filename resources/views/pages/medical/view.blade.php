@extends('layouts.app-main')
@php
$_title = 'Student Medical Overview';
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
            </svg>Overview
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ $_title }}
    </li>
@endsection
@section('page-content')
    <section>
        <p class="display-6 fw-bolder text-primary">Student Medical Overview</p>
        <div class="row">
            @foreach ($_details as $key => $item)
                <div class="col-lg col-xl">
                    <div class="card  iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0"
                        data-iq-ease="power.out" data-iq-opacity="0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('medical.student-medical-appointment') }}?view={{ $item[0] }}">
                                    <div class="fw-bolder text-primary h4">
                                        {{ strtoupper($item[0]) }}
                                    </div>

                                </a>
                                <div class="text-end">
                                    <h3 class="counter"> {{ strtoupper($item[1]) }}</h3>
                                </div>
                            </div>
                            @foreach ($_courses as $course)
                                <div class="row">
                                    <div class="col-md">{{ $course->course_code }}</div>
                                    <div class="col-md">
                                        {{-- {{ count($course[$item[2]]) }} --}}
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-8">
                <form action="{{ request()->url() }}" method="get">
                    {{-- <input type="hidden" name="_course" value="{{ base64_encode($_course->id) }}"> --}}
                    @if (request()->input('_academic'))
                        <input type="hidden" name="_academic" value="{{ request()->input('_academic') }}">
                    @endif
                    @if (request()->input('_year_level'))
                        <input type="hidden" name="_year_level" value="{{ request()->input('_year_level') }}">
                    @endif
                    @if (request()->input('_sort'))
                        <input type="hidden" name="_sort" value="{{ request()->input('_sort') }}">
                    @endif
                    @if (request()->input('view'))
                        <input type="hidden" name="view" value="{{ request()->input('view') }}">
                    @endif
                    <div class="row">
                        <div class="col-6">
                            <small class="text-primary"><b>SEARCH STUDENT NAME</b></small>
                            <div class="form-group search-input">
                                <input type="search" class="form-control" placeholder="Search Pattern: Lastname, Firstname"
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
                            {{ request()->input('view') ? strtoupper(request()->input('view')) : 'WAITING FOR SCHEDULED' }}
                            LIST
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
                    @if (request()->input('view') == 'scheduled')
                        <a href="{{ route('medical.download-appointment') }}"
                            class="btn btn-sm text-white btn-info">DONWLOAD APPOINTMENTS</a>
                    @endif
                    <span class="text-muted h6">
                        No. Result: <b>{{ count($_applicants) }}</b>
                    </span>
                </div>
                @if (count($_applicants) > 0)
                    @foreach ($_applicants as $_data)
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        @if ($_data->student->enrollment_assessment)
                                            <span
                                                class="badge {{ $_data->student->enrollment_assessment->course_id == 3 ? 'bg-info' : 'bg-primary' }}">{{ $_data->student->enrollment_assessment->course->course_code }}</span>
                                        @endif
                                        -
                                        <span><b>{{ $_data->student->account ? $_data->student->account->student_number : '-' }}</b></span>
                                        <a href="?_student={{ base64_encode($_data->id) }}">
                                            <div class="mt-2 h4 fw-bolder text-primary">
                                                {{ strtoupper($_data->student->last_name . ', ' . $_data->student->first_name) }}
                                            </div>

                                        </a>
                                        <span> <small>CONTACT NUMBER:</small>
                                            <b>{{ $_data->student ? $_data->student->contact_number : '-' }}</b>
                                        </span>

                                    </div>
                                    <div class="col-md">
                                        @if (request()->input('view') == 'scheduled')
                                            <small class="text-muted fw-bolder">APPOINTMENT SCHEDULE</small>
                                            <div class="badge bg-primary w-100">
                                                <span>{{ $_data->student->student_medical_appointment->appointment_date }}</span>
                                            </div>
                                            <a href="{{ route('medical.student-appointment') }}?appointment={{ base64_encode($_data->id) }}"
                                                class="btn btn-sm btn-outline-info mt-2">APPROVED</a>
                                        @endif
                                        @if (request()->input('view') == 'approved' ||
                                            request()->input('view') == 'passed' ||
                                            request()->input('view') == 'pending' ||
                                            request()->input('view') == 'failed')
                                            @if ($_data->student->student_medical_result)
                                                @if ($_data->student->student_medical_result->is_fit !== null)
                                                    @if ($_data->student->student_medical_result->is_fit === 1)
                                                        <span class="badge bg-primary mb-4">FIT TO ENROLL</span>
                                                    @else
                                                        <span class="badge bg-danger mb-4">FAILED</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-info mb-4">PENDING RESULT</span>
                                                    <a href="{{ route('medical.student-medical-result') . '?result=' . base64_encode(0) . '&student=' . base64_encode($_data->student_id) }}"
                                                        class="btn btn-primary btn-sm w-100 mb-2">FIT</a>
                                                    <a class="btn btn-danger btn-sm w-100 mb-2 btn-medical"
                                                        data-applicant="{{ base64_encode($_data->student_id) }}"
                                                        data-bs-toggle="modal" data-bs-target=".modal-medical-fail">FAIL</a>
                                                @endif
                                                <span
                                                    class="badge bg-secondary">{{ $_data->student->student_medical_result->created_at->format('F d,Y') }}</span>
                                            @else
                                                <a href="{{ route('medical.student-medical-result') . '?result=' . base64_encode(1) . '&student=' . base64_encode($_data->student_id) }}"
                                                    class="btn btn-primary btn-sm w-100 mb-2">FIT</a>
                                                <a class="btn btn-danger btn-sm w-100 mb-2 btn-medical"
                                                    data-applicant="{{ base64_encode($_data->student_id) }}"
                                                    data-bs-toggle="modal" data-bs-target=".modal-medical-fail">FAIL</a>
                                                <a class="btn btn-info btn-sm w-100 text-white mb-2 btn-medical"
                                                    data-applicant="{{ base64_encode($_data->student_id) }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target=".modal-medical-pending">PENDING</a>
                                            @endif
                                        @endif
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
                @foreach ($_results as $key => $item)
                    <div class="col-lg col-xl">
                        <div class="card  iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50" data-iq-rotate="0"
                            data-iq-ease="power.out" data-iq-opacity="0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('medical.overview') }}?view={{ $item[0] }}">
                                        <div class="fw-bolder text-primary h4">
                                            {{ strtoupper($item[0]) }}
                                        </div>

                                    </a>
                                    <div class="text-end">
                                        <h3 class="counter"> {{ strtoupper($item[1]) }}</h3>
                                    </div>
                                </div>
                                @foreach ($_courses as $course)
                                    <div class="row">
                                        <div class="col-md">{{ $course->course_code }}</div>
                                        <div class="col-md">
                                            -
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endforeach
                @foreach ($_courses as $_course)
                    <div class="col-md">
                        <a
                            href="{{ route('applicant-virtual-briefing') }}?_course={{ base64_encode($_course->id) }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}">
                            <div class="card  iq-purchase" data-iq-gsap="onStart" data-iq-position-y="50"
                                data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out"
                                data-iq-opacity="0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h5 class="text-primary">
                                            {{ $_course->course_name }}
                                        </h5>
                                        <a href="javascript:void(0);">
                                            <svg width="32" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
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
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h3 class="conter"></h3>
                                        {{-- <p class="mb-0 ms-2">+3 last/d</p> --}}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <div class="modal fade modal-medical-fail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Medical Examination - Fail</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('medical.student-medical-result') }}" method="get">
                        <div class="form-group">
                            <label for="" class="form-label fw-bolder">REMARKS</label>
                            <input type="text" name="remarks" class="form-control">
                            <input type="hidden" name="result" value="{{ base64_encode(2) }}">
                            <input type="hidden" name="applicant" class="applicant">
                        </div>
                        <button type="submit" class="btn btn-outline-primary">SUBMIT</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-medical-pending" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Medical Examination - Pending</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('medical.student-medical-result') }}" method="get">
                        <div class="form-group">
                            <label for="" class="form-label fw-bolder">REMARKS</label>
                            <input type="text" name="remarks" class="form-control">
                            <input type="hidden" name="applicant" class="applicant">
                        </div>
                        <button type="submit" class="btn btn-outline-primary">SUBMIT</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection




@section('js')
    <script>
        $(document).on('click', '.btn-medical', function() {
            var applicant = $(this).data('applicant');
            $('.applicant').val(applicant)
            console.log('Applicant: ' + applicant)
        });
        /*  $(document).on('click', '.course-btn', function() {
             var data = $(this).data('course')
             $.get('applicant-list?course=' + data, function(respond) {
                 respond.applicant.forEach(element => {
                     $.get('applicant/notification?_applicant=' + element.id, function(respond) {
                         if (respond.data.respond == '200') {
                             console.info(respond.data.message)
                         }
                     })
                 });
             })
         }) */
    </script>
@endsection
