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
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Summary Overview</h4>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive mt-4">
                    <table id="basic-table" class="table table-striped mb-0" role="grid">
                        <thead>
                            <tr class="text-center">
                                <th>COURSE</th>
                                @foreach ($_table_content as $key => $item)
                                    <th>
                                        {{ strtoupper($item[0]) }}
                                        <br>

                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($_courses as $_course)
                                <tr>
                                    <td>{{ $_course->course_name }}</td>
                                    @foreach ($_table_content as $key => $item)
                                        @php
                                        $_route_link =
                                        route('medical.student-medical-appointment')."?".(request()->input('_academic') ?
                                        '_academic=' . request()->input('_academic') . '&' :
                                        '')."view=".$item[0];
                                        @endphp

                                        <td>
                                             <a href="{{ $_route_link }}&_course={{ base64_encode($_course->id) }}">{{ count($_course[$item[1]]) }}</a>
                                        <a href="{{ route('medical.export-medical-student-list') . '?category=' . $item[1] }}{{ request()->input('_academic') ? '&_academic=' . request()->input('_academic') : '' }}&_course={{ base64_encode($_course->id) }}"
                                            class="float-end">
                                            <svg width="20" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.2301 7.29052V3.2815C11.2301 2.85523 11.5701 2.5 12.0001 2.5C12.3851 2.5 12.7113 2.79849 12.763 3.17658L12.7701 3.2815V7.29052L17.55 7.29083C19.93 7.29083 21.8853 9.23978 21.9951 11.6704L22 11.8861V16.9254C22 19.373 20.1127 21.3822 17.768 21.495L17.56 21.5H6.44C4.06 21.5 2.11409 19.5608 2.00484 17.1213L2 16.9047L2 11.8758C2 9.4281 3.87791 7.40921 6.22199 7.29585L6.43 7.29083H11.23V13.6932L9.63 12.041C9.33 11.7312 8.84 11.7312 8.54 12.041C8.39 12.1959 8.32 12.4024 8.32 12.6089C8.32 12.7659 8.3648 12.9295 8.45952 13.0679L8.54 13.1666L11.45 16.1819C11.59 16.3368 11.79 16.4194 12 16.4194C12.1667 16.4194 12.3333 16.362 12.4653 16.2533L12.54 16.1819L15.45 13.1666C15.75 12.8568 15.75 12.3508 15.45 12.041C15.1773 11.7594 14.7475 11.7338 14.4462 11.9642L14.36 12.041L12.77 13.6932V7.29083L11.2301 7.29052Z"
                                                    fill="currentColor"></path>
                                            </svg> </a>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
                                            <a href="{{ route('medical.student-appointment') }}?appointment={{ base64_encode($_data->student->student_medical_appointment->id) }}"
                                                class="btn btn-sm btn-outline-info mt-2">APPROVED</a>
                                        @endif
                                        @if (request()->input('view') == 'waiting for result' ||
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
                                                    <a href="{{ route('medical.student-medical-result') . '?result=' . base64_encode(1) . '&student=' . base64_encode($_data->student->student_medical_appointment->id) }}"
                                                        class="btn btn-primary btn-sm w-100 mb-2">FIT</a>
                                                    <a class="btn btn-danger btn-sm w-100 mb-2 btn-medical"
                                                        data-applicant="{{ base64_encode($_data->student->student_medical_appointment->id) }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target=".modal-medical-fail">FAIL</a>
                                                @endif
                                                <span
                                                    class="badge bg-secondary">{{ $_data->student->student_medical_result->created_at->format('F d,Y') }}</span>
                                            @else
                                                <a href="{{ route('medical.student-medical-result') . '?result=' . base64_encode(1) . '&student=' . base64_encode($_data->student->student_medical_appointment->id) }}"
                                                    class="btn btn-primary btn-sm w-100 mb-2">FIT</a>
                                                <a class="btn btn-danger btn-sm w-100 mb-2 btn-medical"
                                                    data-applicant="{{ base64_encode($_data->student->student_medical_appointment->id) }}"
                                                    data-bs-toggle="modal" data-bs-target=".modal-medical-fail">FAIL</a>
                                                <a class="btn btn-info btn-sm w-100 text-white mb-2 btn-medical"
                                                    data-applicant="{{ base64_encode($_data->student->student_medical_appointment->id) }}"
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
                            <input type="hidden" name="student" class="applicant">
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
