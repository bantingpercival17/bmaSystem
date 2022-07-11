@section('student-enrollment-card')
    @foreach ($_students as $_student)
        @if (Auth::user()->staff->current_academic()->semester == 'First Semester')
            @php
                $_course_color = $_student->enrollment_application->course_id == 1 ? 'bg-info' : '';
                $_course_color = $_student->enrollment_application->course_id == 2 ? 'bg-primary' : $_course_color;
                $_course_color = $_student->enrollment_application->course_id == 3 ? 'bg-warning text-white' : $_course_color;
            @endphp
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
                                                {{ $_student->enrollment_application->course->course_name }}
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
                                                    {{ strtoupper($_student->enrollment_application->strand) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if ($_student->enrollment_assessment)
                                    <div class="clearance-status mt-2">
                                        <div class="row">
                                            <div class="col-md">
                                                <small class="fw-bolder">ACADEMIC CLEARANCE</small>
                                                <label for=""
                                                    class="h5 {{ $_student ? ($_student->academic_clearance_status() != 'NO SECTION' ? ($_student->academic_clearance_status() == 'NOT CLEARED' ? 'text-danger' : 'text-primary') : 'text-muted') : 'text-muted' }} fw-bolder">{{ $_student ? $_student->academic_clearance_status() : '' }}</label>
                                            </div>
                                            <div class="col-md ps-0">
                                                <small class="fw-bolder">NON-ACADEMIC CLEARANCE</small>
                                                <label for=""
                                                    class="h5 {{ $_student ? ($_student->non_academic_clearance_status() != 'NO SECTION' ? ($_student->non_academic_clearance_status() == 'NOT CLEARED' ? 'text-danger' : 'text-primary') : 'text-muted') : 'text-muted' }} fw-bolder">{{ $_student ? $_student->non_academic_clearance_status() : '' }}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="enrollment-assessment mt-0 me-3">
                            <span class="h5 fw-bolder text-primary">ENROLLMENT ASSESSMENT</span>
                            <form action="{{ route('registrar.enrollment-assessment') }}"
                                class="form-assessment {{ base64_encode($_student->id) }}" method="post"
                                data-form="{{ base64_encode($_student->id) }}">
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
                                                        {{ $_student->enrollment_application->course_id == $course->id ? 'selected' : '' }}>
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
                                    <button type="button" class="btn btn-info btn-sm text-white">FOR ASSESSMENT</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="row no-gutters">
                    <div class="col-md-6 col-lg-4">
                        <img src="{{ $_student ? $_student->profile_pic($_student->account) : 'http://bma.edu.ph/img/student-picture/midship-man.jpg' }}"
                            class="card-img" alt="student-profile">
                    </div>
                    <div class="col-md-6 col-lg-8">
                        <div class="card-body">
                            <h4 class="card-title text-primary">
                                <b>{{ $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name) : 'MIDSHIPMAN NAME' }}</b>
                            </h4>
                            <p class="card-text">
                                <span>{{ $_student ? ($_student->account ? $_student->account->student_number : '-') : '-' }}</span>
                                <br>
                                <span>
                                    {{ $_student ? ($_student->enrollment_assessment->course ? $_student->enrollment_assessment->course->course_name : '-') : '-' }}
                                </span>
                                <br>
                                <span>
                                    {{ $_student ? $_student->enrollment_assessment->year_and_section($_student->enrollment_assessment) : '- | -' }}
                                </span>
                            </p>

                            @if ($_student->enrollment_assessment->academic_id == Auth::user()->staff->current_academic()->id)
                                @if ($_student->enrollment_assessment->payment_assessments)
                                    @if ($_student->enrollment_assessment->payment_assessments->payment_assessment_paid)
                                        <span class="badge bg-primary">Offical Enrolled</span>
                                    @else
                                        <span class="badge bg-info text-white">Payment </span>
                                    @endif
                                @else
                                    <span class="badge bg-info text-white">Assessment Fees</span>
                                @endif
                            @else
                                <label for="" class="text-muted fw-bolder"><small>Clearance
                                        Status</small></label>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <label for="" class="text-info fw-bolder">Academic</label> <br>
                                        <label for=""
                                            class="h5 {{ $_student ? ($_student->academic_clearance_status() != 'NO SECTION' ? ($_student->academic_clearance_status() == 'NOT CLEARED' ? 'text-danger' : 'text-primary') : 'text-muted') : 'text-muted' }} fw-bolder">{{ $_student ? $_student->academic_clearance_status() : '' }}</label>
                                    </div>
                                    <div>
                                        <label for="" class="text-info fw-bolder">Non-Academic</label> <br>
                                        <label for=""
                                            class="h5 {{ $_student ? ($_student->non_academic_clearance_status() != 'NO SECTION' ? ($_student->non_academic_clearance_status() == 'NOT CLEARED' ? 'text-danger' : 'text-primary') : 'text-muted') : 'text-muted' }} fw-bolder">{{ $_student ? $_student->non_academic_clearance_status() : '' }}</label>
                                    </div>
                                </div>
                                <form action="{{ route('registrar.enrollment-assessment') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="_student" value="{{ base64_encode($_student->id) }}">
                                    <div class="row">
                                        <div class="col-md">
                                            <a href="{{ route('registrar.student-clearance') }}?_student={{ base64_encode($_student->id) }}"
                                                class="btn btn-primary btn-sm w-100">View</a>
                                        </div>
                                        <div class="col-md">
                                            <button class="btn btn-info btn-sm text-white w-100">For
                                                Assessment</button>
                                        </div>
                                    </div>
                                </form>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
