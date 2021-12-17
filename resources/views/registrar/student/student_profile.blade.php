@extends('app')
@section('page-title', 'Student')
@section('page-navigation')
    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li class="breadcrumb-item active">Student</li>
    </ol>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-md-4">
            <form action="" method="get" class="card">
                <div class="card-body">
                    <label for="" class="text-muted h5">| SEARCH STUDENT</label>
                    <div class="form-group">
                        <label for="" class="text-success">STUDENT NAME</label>
                        <input type="text" class="form-control" name="_student">


                    </div>
                    <p class="text-muted h6"> Format to search: Last name then use a coma to separate the
                        First Name </p>
                    <div class="form-group">
                        <label for="" class="text-success">COURSE</label>
                        <select name="_course" id="" class="form-control" disabled>
                            @foreach ($_course as $course)
                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="callout callout-success">
                @php
                    //$_course = $_student ? $_student->current_enrolled_status->courseOffer : '';
                    $_student_name = $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name . ' ' . $_student->middle_name) : 'COMPLETE NAME';
                    $_student_no = $_student ? $_student->account->student_number /* . ' | ' . $_course->course_code */ : 'STUDENT NUMBER';
                    // $_profile = $_student ? ($_course->department === 'COLLEGE' ? '/img/1x1 COLLEGE/' . $_student->user->client_code . '.png' : '/img/1x1 SHS/' . $_cadet->user->client_code . '.jpg') : '/img/midship-man.jpg';
                @endphp
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <h5><b class="text-muted">CADET'S INFORMATION</b></h5>
                            <h4 class="{{ $_student ? 'text-success' : 'text-muted' }}"><b>{{ $_student_name }}</b>
                            </h4>
                            <h5 class="{{ $_student ? 'text-success' : 'text-muted' }}"><b>{{ $_student_no }}</b></h5>
                        </div>
                        <div class="col-md-3">
                            @if (file_exists(public_path('$_profile')))
                                <img class="img-circle elevation-2" src="{{ asset('$_profile') }}" alt="User Avatar"
                                    height="120px">
                            @else
                                <img class="img-circle elevation-2" src="{{ asset('/img/midship-man.jpg') }}"
                                    alt="User Avatar" height="120px">
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            {{-- Cadet's Information --}}
            {{-- <div class="card card-prirary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title"><b class="text-success">PERSONAL INFORMATION</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                </div>
            </div> --}}
            {{-- Cadet's Enrollment Details --}}
            {{-- <div class="card card-prirary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title"><b class="text-success">ENROLLMENT DETAILS</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <a href="/admin/v2/students/view?edit=true&student-profile={{ request()->input('student-profile') }}"
                        class="btn btn-secondary">EDIT</a>
                    @foreach ($_student->enrollment_details as $_details)
                        <div
                            class="callout callout-{{ $_details->_academic->academic_status == 1 ? 'success' : 'info' }} card ">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md">
                                        <label for="" class="text-muted"> <small> ACDEMIC YEAR: </small> </label>
                                        <br>
                                        <label for="" class="text-muted">
                                            <b> {{ $_details->_academic->school_year }} </b>
                                        </label>
                                    </div>
                                    <div class="col-md">
                                        <label for="" class="text-muted"> <small> SEMESTER: </small> </label> <br>
                                        <label for="" class="text-muted">
                                            <b> {{ $_details->_academic->semester }} </b>
                                        </label>
                                    </div>
                                    <div class="col-md">
                                        <label for="" class="text-muted"> <small> GRADE / CLASS: </small> </label>
                                        <br>
                                        <label for="" class="text-muted">
                                            <b>
                                                @php
                                                    $_level = $_details->year_level;
                                                    $_level = $_level == 4 ? $_level . 'th' : $_level;
                                                    $_level = $_level == 3 ? $_level . 'rd' : $_level;
                                                    $_level = $_level == 2 ? $_level . 'nd' : $_level;
                                                    $_level = $_level == 1 ? $_level . 'st' : $_level;
                                                    echo $_details->course_id == 3 ? 'GRADE ' . $_level : $_level . ' CLASS';
                                                    $_assessment = $_details->assessments($_details);
                                                @endphp
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md">
                                        <label for="" class="text-muted"> <small> COURSE: </small> </label> <br>
                                        <label for="" class="text-muted">
                                            <b> {{ $_details->courseOffer->course_name }} </b>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="text-muted"> <small> BRIDGING PROGRAM: </small>
                                        </label> <br>
                                        <label for="" class="text-muted">
                                            @if (request()->input('edit'))
                                                <form action="/admin/v2/students/update-assessment" method="post">
                                                    @csrf
                                                    <input type="hidden" name="enrollment_id"
                                                        value="{{ $_details->id }}">
                                                    <input type="text" class="form-control" name="data-update"
                                                        value="{{ $_details->bridging_program }}">
                                                </form>
                                            @else
                                                <b> {{ $_details->bridging_program == 'with' ? 'WITH' : 'WITHOUT' }}
                                                </b>
                                            @endif

                                        </label>
                                    </div>
                                    <div class="col-md">
                                        <label for="" class="text-muted"> <small> ENROLLED DATE: </small> </label>
                                        <br>
                                        <label for="" class="text-muted">
                                            @if ($_assessment)
                                                @if ($_assessment->student_upon_enrollment)
                                                    <b> {{ date_format(date_create($_assessment->student_upon_enrollment->created_at), 'M d, Y') }}
                                                    </b>
                                                @else
                                                    <b>-</b>
                                                @endif

                                            @else
                                                <b>-</b>
                                            @endif

                                        </label>
                                    </div>
                                    <div class="col-md-1">
                                        @if ($_assessment)
                                            @if ($_assessment->upon_enrollment)
                                                <a href="/registrar/v2/students/certificate-of-enrollment?student={{ Crypt::encrypt($_student->id) }}&academic={{ Crypt::encrypt($_details->academic_id) }}"
                                                    class="btn btn-info text-white"> <span
                                                        class="fa fa-print"></span></a>
                                            @endif
                                        @endif

                                    </div>


                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div> --}}
            {{-- Academic Grades --}}
            {{-- <div class="card card-prirary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title"><b class="text-success">ACADEMIC GRADE</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <label for="" class="h3 text-info">| SOON TO BE REALSE</label>
                    <p>Grading System is currently, under go a testing run of the Faculty member and Department Heads to
                        verify the correctiveness formulas and encoding of grades</p>
                </div>
            </div> --}}
            {{-- Payments --}}
            {{-- <div class="card card-prirary">
                <div class="card-header">
                    <h3 class="card-title"><b class="text-success">PAYMENTS</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($_student->assessment as $_assessment_fee)
                        <div class="callout callout-info">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-2">
                                        <small class="text-muted">ACADEMIC ID:</small>
                                        <br>
                                        <label for="" class="text-muted">
                                            <b> {{ $_assessment_fee->academic_id }} </b>
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">ASSESSMENT ID:</small>
                                        <br>
                                        <label for="" class="text-muted">
                                            <b> {{ $_assessment_fee->id }} </b>
                                        </label>
                                    </div>
                                    <div class="col-md-5">
                                        <small class="text-muted">ACADEMIC YEAR:</small>
                                        <br>
                                        <label for="" class="text-muted">
                                            <b> {{ $_assessment_fee->academicYear->school_year . ' | ' . $_assessment_fee->academicYear->semester }}
                                            </b>
                                        </label>
                                    </div>
                                    <div class="col-md">
                                        <small class="text-muted">MODE OF PAYMENT:</small>
                                        <br>
                                        <label for="" class="text-muted">
                                            <b> {{ $_assessment_fee->mode_payment }} </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md">
                                        <small class="text-muted">TOTAL FEES:</small>
                                        <br>
                                        <label for="" class="text-muted">
                                            <b> {{ $_assessment_fee->total_enrollment_payment }} </b>
                                        </label>
                                    </div>
                                    <div class="col-md">
                                        <small class="text-muted">PAYMENT PAID:</small>
                                        <br>
                                        <label for="" class="text-muted">
                                            <b> {{ number_format($_assessment_fee->assessment_payment($_assessment_fee->id), 2) }}
                                            </b>
                                        </label>
                                    </div>
                                    <div class="col-md">
                                        <small class="text-muted">REMAINING BALANCE:</small>
                                        <br>
                                        <label for="" class="text-muted">
                                            <b> {{ $_assessment_fee->total_enrollment_payment - $_assessment_fee->assessment_payment($_assessment_fee->id) }}
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <label for="" class="text-primary"><b>| TRANSACTION HISTORY</b></label>
                                <div class="container">
                                    @foreach ($_assessment_fee->student_payment as $_pay)
                                        @if (request()->input('edit'))
                                            <form action="/admin/v2/students/update-payment-assessment" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md">
                                                        <small class="text-muted">ACAD ID:</small>
                                                        <br>
                                                        <input type="text" class="form-control" name="acaedmic"
                                                            value="{{ $_pay->academic_id }}">
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted">ASSESS ID:</small>
                                                        <br>
                                                        <input type="hidden" name="id" value="{{ $_pay->id }}">
                                                        <input type="text" class="form-control" name="data-update"
                                                            value="{{ $_pay->assessment_id }}">
                                                    </div>
                                                    <div class="col-md">

                                                        <small class="text-muted">OR NUMBER:</small>
                                                        <br>
                                                        <input type="text" class="form-control" name="data-or"
                                                            value="{{ $_pay->or_number }}">
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted">AMOUNT:</small>
                                                        <br>
                                                        <input type="text" class="form-control" name="data-payment"
                                                            value="{{ $_pay->payment_amount }}">
                                                    </div>
                                                    <div class="col-md">
                                                        <button class="btn btn-info btn-block"
                                                            type="submit">UPDATE</button>
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted">REMARKS:</small>
                                                        <br>
                                                        <label for="" class="text-muted">
                                                            <b> {{ $_pay->remarks }}
                                                            </b>
                                                        </label>
                                                    </div>
                                                    <div class="col-md">
                                                        <small class="text-muted">TRANSACTION DATE:</small>
                                                        <br>
                                                        <label for="" class="text-muted">
                                                            <b> {{ date_format(date_create($_pay->created_at), 'M d, Y') }}
                                                            </b>
                                                        </label>
                                                    </div>
                                                </div>
                                            </form>

                                        @else
                                            <div class="row">
                                                <div class="col-md">
                                                    <small class="text-muted">OR NUMBER:</small>
                                                    <br>
                                                    <label for="" class="text-muted">
                                                        <b> {{ $_pay->or_number }} </b>
                                                    </label>
                                                </div>
                                                <div class="col-md">
                                                    <small class="text-muted">AMOUNT:</small>
                                                    <br>
                                                    <label for="" class="text-muted">
                                                        <b> {{ $_pay->payment_amount }}
                                                        </b>
                                                    </label>
                                                </div>
                                                <div class="col-md">
                                                    <small class="text-muted">REMARKS:</small>
                                                    <br>
                                                    <label for="" class="text-muted">
                                                        <b> {{ $_pay->remarks }}
                                                        </b>
                                                    </label>
                                                </div>
                                                <div class="col-md">
                                                    <small class="text-muted">TRANSACTION DATE:</small>
                                                    <br>
                                                    <label for="" class="text-muted">
                                                        <b> {{ date_format(date_create($_pay->created_at), 'M d, Y') }}
                                                        </b>
                                                    </label>
                                                </div>
                                            </div>
                                        @endif

                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div> --}}
        </div>
    </div>

@endsection
