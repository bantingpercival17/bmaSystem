@section('student-enrollment-card')

    @foreach ($_students as $_student)
        @php
            if ($_student->enrollment_application) {
                $_course_color = $_student->enrollment_application->course_id == 1 ? 'bg-info' : '';
                $_course_color = $_student->enrollment_application->course_id == 2 ? 'bg-primary' : $_course_color;
                $_course_color = $_student->enrollment_application->course_id == 3 ? 'bg-warning text-white' : $_course_color;
            } else {
                $_course_color = 'text-muted';
                if ($_student->enrollment_assessment) {
                    $_course_color = $_student->enrollment_assessment->course_id == 1 ? 'bg-info' : '';
                    $_course_color = $_student->enrollment_assessment->course_id == 2 ? 'bg-primary' : $_course_color;
                    $_course_color = $_student->enrollment_assessment->course_id == 3 ? 'bg-warning text-white' : $_course_color;
                }
            }

        @endphp
        @if (Auth::user()->staff->current_academic()->semester == 'First Semester')
            <div class="mb-5">
                <div class="row no-gutters">
                    <div class="col-lg-4">
                        <img src="{{ $_student ? $_student->profile_pic($_student->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="student-profile">
                    </div>
                    <div class="col-lg-8 ps-0">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="card-information">
                                    <h4 class="card-title text-primary mb-0">
                                        <b>{{ $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name . " ".$_student->extenstion_name ) : 'MIDSHIPMAN NAME' }}</b>
                                    </h4>
                                    <div class="row">
                                        <div class="col-md">
                                            <small class="fw-bolder">COURSE : </small><br>
                                            <span class="badge {{ $_course_color }}">
                                                {{ ($_student->enrollment_applicantion ? $_student->enrollment_application->course->course_name : $_student->enrollment_assessment) ? $_student->enrollment_assessment->course->course_name : '' }}
                                            </span>
                                        </div>
                                        <div class="col-md">
                                            <small class="fw-bolder">STUDENT NUMBER :</small><br>
                                            @if ($_student->account)
                                                <span class="badge bg-primary">
                                                    {{ $_student->account->student_number }}</span>
                                            @else
                                                <span class="badge bg-primary">NEW STUDENT</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if (!$_student->account)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="fw-bolder">SENIOR HIGH SCHOOL STRAND</small><br>

                                                <span class="badge bg-primary">
                                                    {{ $_student->enrollment_application ? strtoupper($_student->enrollment_application->strand) : '' }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if ($_student->enrollment_application->enrollment_category == 'SBT ENROLLMENT')
                                    <div class="sbt-enrollment">
                                        <small class="text-muted fw-bolder">SHIPBOARD ENROLLMENT</small><br>
                                        <span class="badge bg-primary">
                                            CLEARED ON OBT OFFICE</span>
                                    </div>
                                @else
                                    @if ($_student->enrollment_assessment)
                                        <div class="clearance-status mt-2">
                                            <div class="row">
                                                <div class="col-md">
                                                    <small class="fw-bolder">MEDICAL RESULT STATUS</small> <br>
                                                    @if ($_student->student_medical_appointment)
                                                        @if ($_student->student_medical_result)
                                                            @if ($_student->student_medical_result->is_fit !== null)
                                                                @if ($_student->student_medical_result->is_fit === 1)
                                                                    <span class="badge bg-primary mb-4">FIT TO ENROLL</span>
                                                                @else
                                                                    <span class="badge bg-danger mb-4">FAILED</span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-info mb-4">PENDING RESULT</span>
                                                            @endif
                                                            <span
                                                                class="badge bg-secondary">{{ $_student->student_medical_result->created_at->format('F d,Y') }}</span>
                                                        @else
                                                            <label for="" class="fw-bolder text-muted">WAIT FOR
                                                                MEDICAL
                                                                RESULT</label>
                                                        @endif
                                                    @else
                                                        <label for="" class="fw-bolder text-muted">NO MEDICAL
                                                            SCHEDULED</label>
                                                    @endif


                                                </div>
                                                {{-- <div class="col-md">
                                            <small class="fw-bolder">ACADEMIC CLEARANCE</small>
                                            <label for=""
                                                class="h5 {{ $_student ? ($_student->academic_clearance_status() != 'NO SECTION' ? ($_student->academic_clearance_status() == 'NOT CLEARED' ? 'text-danger' : 'text-primary') : 'text-muted') : 'text-muted' }} fw-bolder">{{ $_student ? $_student->academic_clearance_status() : '' }}</label>
                                        </div>
                                        <div class="col-md ps-0">
                                            <small class="fw-bolder">NON-ACADEMIC CLEARANCE</small>
                                            <label for=""
                                                class="h5 {{ $_student ? ($_student->non_academic_clearance_status() != 'NO SECTION' ? ($_student->non_academic_clearance_status() == 'NOT CLEARED' ? 'text-danger' : 'text-primary') : 'text-muted') : 'text-muted' }} fw-bolder">{{ $_student ? $_student->non_academic_clearance_status() : '' }}</label>
                                        </div> --}}
                                            </div>
                                        </div>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    @if ($_student->enrollment_assessment_v2)
                        <div class="card-body">

                            <p>
                                <span class="badge bg-primary">
                                    Enrollment Assessment Done
                                </span> <br>

                                <small
                                    class="fw-bolder text-muted">{{ $_student->enrollment_assessment_v2->staff->user->name . ' - ' . $_student->enrollment_assessment_v2->created_at->format('M d,Y') }}
                                </small>
                            </p>
                            @if (!$_student->enrollment_assessment_v2->payment_assessment)
                                <div class="card-body">
                                    <div class="enrollment-assessment mt-0 me-3">
                                        <span class="h5 fw-bolder text-primary">ENROLLMENT RE-ASSESSMENT</span>
                                        <form action="{{ route('registrar.enrollment-assessment') }}" method="post"
                                            id="{{ base64_encode($_student->id) }}">
                                            @csrf
                                            <input type="hidden" name="_student"
                                                value="{{ base64_encode($_student->id) }}">
                                            <div class="row">
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <small class="fw-bolder">COURSE : </small>
                                                        <select name="_course"
                                                            class="form-select form-select-sm mb-3 shadow-none input-course">
                                                            @foreach ($_courses as $course)
                                                                <option value="{{ $course->id }}"
                                                                    {{ $_student->enrollment_application
                                                                        ? ($_student->enrollment_application->course_id == $course->id
                                                                            ? 'selected'
                                                                            : '')
                                                                        : '' }}>
                                                                    {{ $course->course_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <small class="fw-bolder">CURRICULUM : </small>
                                                        <select name="_curriculum"
                                                            class="form-select form-select-sm mb-3 shadow-none input-curriculum">
                                                            @foreach ($_curriculums as $curriculum)
                                                                <option value="{{ $curriculum->id }}"
                                                                    {{ $_student->enrollment_assessment_v2->curriculum_id == $curriculum->id ? 'selected' : '' }}>
                                                                    {{ $curriculum->curriculum_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if (!$_student->account)
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <small class="fw-bolder">BRIDGING PROGRAM : </small>
                                                            <select name="_bridging_program"
                                                                class="form-select form-select-sm mb-3 shadow-none">
                                                                <option value="with">WITH BRIDGING</option>
                                                                <option value="without">WITHOUT BRIDGING</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="float-end">
                                                <button type="button" class="btn btn-info btn-sm text-white btn-assessment"
                                                    data-form="{{ base64_encode($_student->id) }}">FOR
                                                    ASSESSMENT</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="card-body">
                            <div class="enrollment-assessment mt-0 me-3">
                                <span class="h5 fw-bolder text-primary">ENROLLMENT ASSESSMENT</span>
                                <form action="{{ route('registrar.enrollment-assessment') }}" method="post"
                                    id="{{ base64_encode($_student->id) }}">
                                    @csrf
                                    <input type="hidden" name="_student" value="{{ base64_encode($_student->id) }}">
                                    <div class="row">
                                        <div class="col-md">
                                            <div class="form-group">
                                                <small class="fw-bolder">COURSE : </small>
                                                <select name="_course"
                                                    class="form-select form-select-sm mb-3 shadow-none input-course">
                                                    @foreach ($_courses as $course)
                                                        <option value="{{ $course->id }}"
                                                            {{ $_student->enrollment_application
                                                                ? ($_student->enrollment_application->course_id == $course->id
                                                                    ? 'selected'
                                                                    : '')
                                                                : '' }}>
                                                            {{ $course->course_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <small class="fw-bolder">CURRICULUM : </small>
                                                <select name="_curriculum"
                                                    class="form-select form-select-sm mb-3 shadow-none input-curriculum">
                                                    @foreach ($_curriculums as $curriculum)
                                                        <option value="{{ $curriculum->id }}">
                                                            {{ $curriculum->curriculum_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if (!$_student->account)
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <small class="fw-bolder">BRIDGING PROGRAM : </small>
                                                    <select name="_bridging_program"
                                                        class="form-select form-select-sm mb-3 shadow-none">
                                                        <option value="with">WITH BRIDGING</option>
                                                        <option value="without">WITHOUT BRIDGING</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="float-end">
                                        <button type="button" class="btn btn-info btn-sm text-white btn-assessment"
                                            data-form="{{ base64_encode($_student->id) }}">FOR
                                            ASSESSMENT</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @else
            <div class="mb-5">
                <div class="row no-gutters">
                    <div class="col-lg-4">
                        <img src="{{ $_student ? $_student->profile_pic($_student->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="student-profile">
                    </div>
                    <div class="col-lg-8 ps-0">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="card-information">
                                    <h4 class="card-title text-primary mb-0">
                                        <b>{{ $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name) : 'MIDSHIPMAN NAME' }}</b>
                                    </h4>
                                    <div class="row">
                                        <div class="col-md">
                                            <small class="fw-bolder">COURSE : </small><br>
                                            <span class="badge {{ $_course_color }}">
                                                {{ ($_student->enrollment_applicantion ? $_student->enrollment_application->course->course_name : $_student->enrollment_assessment) ? $_student->enrollment_assessment->course->course_name : '' }}
                                            </span>
                                        </div>
                                        <div class="col-md">
                                            <small class="fw-bolder">STUDENT NUMBER :</small><br>
                                            @if ($_student->account)
                                                <span class="badge bg-primary">
                                                    {{ $_student->account->student_number }}</span>
                                            @else
                                                <span class="badge bg-primary">NEW STUDENT</span>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <small class="fw-bolder">YEAR AND SECTION : </small><br>
                                        <span class="badge {{ $_course_color }}">
                                            {{ $_student ? $_student->enrollment_assessment->year_and_section($_student->enrollment_assessment) : '- | -' }}
                                        </span>
                                    </div>
                                    @if (!$_student->account)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="fw-bolder">SENIOR HIGH SCHOOL STRAND</small><br>

                                                <span class="badge bg-primary">
                                                    {{ $_student->enrollment_application ? strtoupper($_student->enrollment_application->strand) : '' }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if ($_student->enrollment_application)
                                    @if ($_student->enrollment_application->enrollment_category == 'SBT ENROLLMENT')
                                        <div class="sbt-enrollment">
                                            <small class="text-muted fw-bolder">SHIPBOARD ENROLLMENT</small><br>
                                            <span class="badge bg-primary">
                                                CLEARED ON OBT OFFICE</span>
                                        </div>
                                    @else
                                        @if ($_student->enrollment_assessment)
                                            <div class="clearance-status mt-2">
                                                <div class="row">
                                                    {{--  <div class="col-md">
                                            <small class="fw-bolder">CLEARANCE STATUS</small> <br>
                                            @if ($_student->student_medical_appointment)
                                                @if ($_student->student_medical_result)
                                                    @if ($_student->student_medical_result->is_fit !== null)
                                                        @if ($_student->student_medical_result->is_fit === 1)
                                                            <span class="badge bg-primary mb-4">FIT TO
                                                                ENROLL</span>
                                                        @else
                                                            <span class="badge bg-danger mb-4">FAILED</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-info mb-4">PENDING RESULT</span>
                                                    @endif
                                                    <span
                                                        class="badge bg-secondary">{{ $_student->student_medical_result->created_at->format('F d,Y') }}</span>
                                                @else
                                                    <label for="" class="fw-bolder text-muted">WAIT FOR
                                                        MEDICAL
                                                        RESULT</label>
                                                @endif
                                            @else
                                                <label for="" class="fw-bolder text-muted">NO MEDICAL
                                                    SCHEDULED</label>
                                            @endif


                                        </div> --}}
                                                    {{-- <div class="col-md">
                                            <small class="fw-bolder">ACADEMIC CLEARANCE</small>
                                            <label for=""
                                                class="h5 {{ $_student ? ($_student->academic_clearance_status() != 'NO SECTION' ? ($_student->academic_clearance_status() == 'NOT CLEARED' ? 'text-danger' : 'text-primary') : 'text-muted') : 'text-muted' }} fw-bolder">{{ $_student ? $_student->academic_clearance_status() : '' }}</label>
                                        </div>
                                        <div class="col-md ps-0">
                                            <small class="fw-bolder">NON-ACADEMIC CLEARANCE</small>
                                            <label for=""
                                                class="h5 {{ $_student ? ($_student->non_academic_clearance_status() != 'NO SECTION' ? ($_student->non_academic_clearance_status() == 'NOT CLEARED' ? 'text-danger' : 'text-primary') : 'text-muted') : 'text-muted' }} fw-bolder">{{ $_student ? $_student->non_academic_clearance_status() : '' }}</label>
                                        </div> --}}
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    @if ($_student->enrollment_assessment_v2)
                        <div class="card-body">
                            <p>
                                <span class="badge bg-primary">
                                    Enrollment Assessment Done
                                </span> <br>

                                <small
                                    class="fw-bolder text-muted">{{ $_student->enrollment_assessment_v2->staff->user->name . ' - ' . $_student->enrollment_assessment_v2->created_at->format('M d,Y') }}
                                </small>
                            </p>
                            @if (!$_student->enrollment_assessment_v2->payment_assessment)
                                <div class="card-body">
                                    <div class="enrollment-assessment mt-0 me-3">
                                        <span class="h5 fw-bolder text-primary">ENROLLMENT RE-ASSESSMENT</span>
                                        <form action="{{ route('registrar.enrollment-assessment') }}" method="post"
                                            id="{{ base64_encode($_student->id) }}">
                                            @csrf
                                            <input type="hidden" name="_student"
                                                value="{{ base64_encode($_student->id) }}">
                                            <div class="row">
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <small class="fw-bolder">COURSE : </small>
                                                        <select name="_course"
                                                            class="form-select form-select-sm mb-3 shadow-none input-course">
                                                            @foreach ($_courses as $course)
                                                                <option value="{{ $course->id }}"
                                                                    {{ $_student->enrollment_application
                                                                        ? ($_student->enrollment_application->course_id == $course->id
                                                                            ? 'selected'
                                                                            : '')
                                                                        : '' }}>
                                                                    {{ $course->course_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="form-group">
                                                        <small class="fw-bolder">CURRICULUM : </small>
                                                        <select name="_curriculum"
                                                            class="form-select form-select-sm mb-3 shadow-none input-curriculum">
                                                            @foreach ($_curriculums as $curriculum)
                                                                <option value="{{ $curriculum->id }}"
                                                                    {{ $_student->enrollment_assessment_v2->curriculum_id == $curriculum->id ? 'selected' : '' }}>
                                                                    {{ $curriculum->curriculum_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if (!$_student->account)
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <small class="fw-bolder">BRIDGING PROGRAM : </small>
                                                            <select name="_bridging_program"
                                                                class="form-select form-select-sm mb-3 shadow-none">
                                                                <option value="with">WITH BRIDGING</option>
                                                                <option value="without">WITHOUT BRIDGING</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="float-end">
                                                <button type="button"
                                                    class="btn btn-info btn-sm text-white btn-assessment"
                                                    data-form="{{ base64_encode($_student->id) }}">FOR
                                                    ASSESSMENT</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="card-body">
                            <div class="enrollment-assessment mt-0 me-3">
                                <span class="h5 fw-bolder text-primary">ENROLLMENT ASSESSMENT</span>
                                <form action="{{ route('registrar.enrollment-assessment') }}" method="post"
                                    id="{{ base64_encode($_student->id) }}">
                                    @csrf
                                    <input type="hidden" name="_student" value="{{ base64_encode($_student->id) }}">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <small class="fw-bolder">COURSE : </small>
                                                <label for="form-control"
                                                    class="form-control form-control-sm">{{ $_student->enrollment_applicantion ? $_student->enrollment_application->course->course_name : $_student->enrollment_assessment->course->course_name }}</label>

                                            </div>
                                        </div>
                                        <div class="col-md">
                                            @if ($_student->enrollment_application)
                                                @if ($_student->enrollment_application->course_id == 3)
                                                    <div class="form-group">
                                                        <small class="fw-bolder">GRADE LEVEL : </small>
                                                        <label for="" class="form-control form-control-sm">
                                                            {{ strtoupper(Auth::user()->staff->convert_year_level($_student->enrollment_assessment->year_level)) }}
                                                        </label>

                                                    </div>
                                                @else
                                                    <div class="form-group">
                                                        <small class="fw-bolder">CLASS LEVEL : </small>
                                                        @php
                                                            $_year_level = $_student->enrollment_assessment->year_level;
                                                            $_year_level = $_student->enrollment_application->enrollment_category == 'SBT ENROLLMENT' ? $_year_level - 1 : $_year_level;
                                                        @endphp
                                                        <label for="" class="form-control form-control-sm">
                                                            {{ strtoupper(Auth::user()->staff->convert_year_level($_year_level)) }}
                                                        </label>

                                                    </div>
                                                @endif
                                            @else
                                            @endif


                                        </div>
                                        @if (!$_student->account)
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <small class="fw-bolder">BRIDGING PROGRAM : </small>
                                                    <select name="_bridging_program"
                                                        class="form-select form-select-sm mb-3 shadow-none">
                                                        <option value="with">WITH BRIDGING</option>
                                                        <option value="without">WITHOUT BRIDGING</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="float-end">
                                        <button type="button" class="btn btn-info btn-sm text-white btn-assessment"
                                            data-form="{{ base64_encode($_student->id) }}">FOR
                                            ASSESSMENT</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endif
    @endforeach
@endsection
