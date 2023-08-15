@section('student-enrollment-card')

    @foreach ($studentsList as $_student)
        <div class="mb-5">
            <div class="row no-gutters">
                <div class="col-lg-4 col-md-4">
                    @if ($_student->profile_picture())
                        <img src="{{ $_student->profile_picture() }}" class="card-img" alt="student-profile">
                    @endif
                </div>
                <div class="col-lg-8 ps-0">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="card-information">
                                <h4 class="card-title text-primary mb-0">
                                    <b>{{ $_student ? strtoupper($_student->last_name . ', ' . $_student->first_name) : 'MIDSHIPMAN NAME' }}</b>
                                </h4>
                                @if ($_student->enrollment_application_v2)
                                    <div class="row">
                                        <div class="col-md">
                                            <small class="fw-bolder">COURSE : </small><br>
                                            <span class="badge {{ $_student->enrollment_application_v2->color_course() }}">
                                                @if ($_student->enrollment_application_v2)
                                                    {{ $_student->enrollment_application_v2->course->course_name }}
                                                @else
                                                    @if ($_student->enrollment_assessment_paid)
                                                        {{ $_student->enrollment_assessment_paid->course->course_name }}
                                                    @else
                                                    @endif
                                                @endif
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
                                            <div class="col-md-12">
                                                <small class="fw-bolder">SENIOR HIGH SCHOOL STRAND</small><br>

                                                <span class="badge bg-primary">
                                                    {{ $_student->enrollment_application_v2 ? strtoupper($_student->enrollment_application_v2->strand) : '' }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($_student->enrollment_application_v2->enrollment_category == 'SBT ENROLLMENT')
                                        <div class="row">
                                            <div class="sbt-enrollment col-md">
                                                <small class="text-muted fw-bolder">SHIPBOARD ENROLLMENT</small><br>
                                                <span class="badge bg-primary">
                                                    CLEARED ON OBT OFFICE</span>
                                            </div>
                                            <div class="sbt-enrollment col-md">
                                                <small class="text-muted fw-bolder">SEA EXPERIENCE</small><br>
                                                <span class="badge bg-primary">
                                                    {{ strtoupper($_student->shipboard_training->shipping_company) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        @if ($_student->enrollment_assessment_paid)
                                            <div class="clearance-status mt-2">
                                                <div class="row">

                                                </div>
                                            </div>
                                            @if (Auth::user()->staff->current_academic()->semester == 'First Semester')
                                                <div class="medical-result">
                                                    <div class="col-md">
                                                        <small class="fw-bolder">MEDICAL RESULT STATUS</small> <br>
                                                        @if ($_student->enrollment_assessment_paid->medical_result)
                                                            @if ($_student->enrollment_assessment_paid->medical_result->is_fit !== null)
                                                                @if ($_student->enrollment_assessment_paid->medical_result->is_fit === 1)
                                                                    <span class="badge bg-primary mb-4">FIT TO
                                                                        ENROLL</span>
                                                                @else
                                                                    <span class="badge bg-danger mb-4">FAILED</span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-info mb-4">PENDING RESULT</span>
                                                            @endif
                                                            <span
                                                                class="badge bg-secondary">{{ $_student->enrollment_assessment_paid->medical_result->created_at->format('F d,Y') }}</span>
                                                        @else
                                                            <label for="" class="fw-bolder text-muted">WAIT FOR
                                                                MEDICAL
                                                                RESULT</label>
                                                        @endif


                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                @else
                                    <span class="badge bg-info">NO ENROLLMENT REGISTRATION</span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @if ($_student->enrollment_application_v2)
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="enrollment-assessment mt-0 me-3">
                            @if ($_student->enrollment_assessment_v2)
                                <span class="h5 fw-bolder text-primary">ENROLLMENT RE-ASSESSMENT</span>
                            @else
                                <span class="h5 fw-bolder text-primary">ENROLLMENT ASSESSMENT</span>
                            @endif

                            <form action="{{ route('registrar.enrollment-assessment') }}" method="post"
                                id="{{ base64_encode($_student->id) }}">
                                @csrf
                                <input type="hidden" name="_student" value="{{ base64_encode($_student->id) }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <small class="fw-bolder">COURSE : </small>
                                            <select name="_course"
                                                class="form-select form-select-sm mb-3 shadow-none input-course">
                                                @foreach ($_courses as $course)
                                                    <option value="{{ $course->id }}"
                                                        {{ $_student->enrollment_application_v2
                                                            ? ($_student->enrollment_application_v2->course_id == $course->id
                                                                ? 'selected'
                                                                : '')
                                                            : '' }}>
                                                        {{ $course->course_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <small class="fw-bolder">CURRICULUM : </small>
                                            <select name="_curriculum"
                                                class="form-select form-select-sm mb-3 shadow-none input-curriculum">
                                                @foreach ($_curriculums as $curriculum)
                                                    <option value="{{ $curriculum->id }}"
                                                        {{ $_student->enrollment_assessment_v2 ? ($_student->enrollment_assessment_v2->curriculum_id == $curriculum->id ? 'selected' : '') : '' }}>
                                                        {{ $curriculum->curriculum_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <small class="fw-bolder">YEAR LEVEL : </small>
                                            <select name="_level" class="form-select form-select-sm mb-3 shadow-none ">
                                                @foreach ($yearLevelList as $item)
                                                    <option value="{{ $item }}"
                                                        {{ $_student->enrollment_year_level() == $item ? 'selected' : '' }}>
                                                        {{ Auth::user()->staff->convert_year_level($item) }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    @if (!$_student->account)
                                        <div class="col-md-6">
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
                                    @if (!$_student->enrollment_assessment_v2)
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                            wire:click='confirmBox("{{ base64_encode($_student->id) }}","{{ base64_encode('disapproved') }}" )'>DISAPPROVE</button>
                                    @endif
                                    {{--  <button type="button" class="btn btn-info btn-sm text-white btn-assessment"
                                        data-form="{{ base64_encode($_student->id) }}">
                                        ASSESS</button> --}}
                                    <button type="button" class="btn btn-info btn-sm text-white"
                                        wire:click='confirmBox("{{ base64_encode($_student->id) }}","{{ base64_encode('approved') }}" )'>
                                        ASSESS
                                    </button>
                                </div>
                            </form>
                        </div>
                        @if ($_student->enrollment_assessment_v2)
                            <p>
                                <span class="badge bg-primary">
                                    Enrollment Assessment Done
                                </span> <br>

                                <small
                                    class="fw-bolder text-muted">{{ $_student->enrollment_assessment_v2->staff->user->name . ' - ' . $_student->enrollment_assessment_v2->created_at->format('M d,Y') }}
                                </small>
                            </p>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    @endforeach
@endsection
@section('script')
    <script>
        /*  $('.btn-assessment').click(function(event) {
                             Swal.fire({
                                 title: 'Enrollment Assessment',
                                 text: "Do you want to submit?",
                                 icon: 'warning',
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
                         }) */
    </script>
@endsection
