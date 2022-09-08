@section('enrollment-step')
    <ul class="list-inline p-0 m-0">
        {{-- Enrollment Application --}}
        @if ($_student->enrollment_application_v2)
            <li>
                <div class="timeline-dots1 border-primary text-primary">
                    <svg width="20" viewBox="0 2 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.67 2H16.34C19.73 2 22 4.38 22 7.92V16.091C22 19.62 19.73 22 16.34 22H7.67C4.28 22 2 19.62 2 16.091V7.92C2 4.38 4.28 2 7.67 2ZM11.43 14.99L16.18 10.24C16.52 9.9 16.52 9.35 16.18 9C15.84 8.66 15.28 8.66 14.94 9L10.81 13.13L9.06 11.38C8.72 11.04 8.16 11.04 7.82 11.38C7.48 11.72 7.48 12.27 7.82 12.62L10.2 14.99C10.37 15.16 10.59 15.24 10.81 15.24C11.04 15.24 11.26 15.16 11.43 14.99Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
                <h5 class="float-left mb-1 text-primary fw-bolder">
                    ENROLLMENT APPLICATION
                </h5>
                <div class="d-inline-block w-100">
                    <p class="mb-0">Enrollment Applicantion Date: <span
                            class="badge bg-info">{{ $_student->enrollment_application_v2->created_at->format('F d,Y') }}</span>
                    </p>
                    <p class="mb-0">Enrollment Applicantion Status: <span
                            class="badge bg-info">{{ $_student->enrollment_application_v2->is_approved ? 'APPROVED' : 'PENDING' }}</span>
                    </p>
                </div>
            </li>
            @if ($_enrollment_status = $_student->enrollment_application_status($_student->enrollment_application_v2->academic)->first())
                <li>
                    <div class="timeline-dots1 border-primary text-primary">
                        <svg width="20" viewBox="0 2 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.67 2H16.34C19.73 2 22 4.38 22 7.92V16.091C22 19.62 19.73 22 16.34 22H7.67C4.28 22 2 19.62 2 16.091V7.92C2 4.38 4.28 2 7.67 2ZM11.43 14.99L16.18 10.24C16.52 9.9 16.52 9.35 16.18 9C15.84 8.66 15.28 8.66 14.94 9L10.81 13.13L9.06 11.38C8.72 11.04 8.16 11.04 7.82 11.38C7.48 11.72 7.48 12.27 7.82 12.62L10.2 14.99C10.37 15.16 10.59 15.24 10.81 15.24C11.04 15.24 11.26 15.16 11.43 14.99Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <h5 class="float-left mb-1 text-primary fw-bolder">
                        ENROLLMENT ASSESSMENT
                    </h5>
                    <div class="d-inline-block w-100">
                        <div class="row">
                            <div class="col-md-6">
                                <small for="" class="text-muted fw-bolder">ACADEMIC YEAR</small> <br>
                                <small class="badge bg-primary">
                                    {{ $_student ? ($_enrollment_status ? strtoupper($_enrollment_status->academic->semester . ' | ' . $_enrollment_status->academic->school_year) : 'SECTION') : 'SECTION' }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small for="" class="text-muted fw-bolder">CURRICULUM</small> <br>
                                <small class="badge bg-primary">
                                    {{ $_student ? ($_enrollment_status ? strtoupper($_enrollment_status->curriculum->curriculum_name) : 'curriculum') : 'curriculum' }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small for="" class="text-muted fw-bolder">COURSE</small> <br>
                                <small class="badge bg-primary">
                                    {{ $_student ? ($_enrollment_status ? strtoupper($_enrollment_status->course->course_name) : 'curriculum') : 'curriculum' }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small for="" class="text-muted fw-bolder">YEAR LEVEL</small> <br>
                                <small class="fw-bolder badge bg-primary">
                                    {{ $_student ? ($_enrollment_status ? strtoupper(Auth::user()->staff->convert_year_level($_enrollment_status->year_level)) : 'YEAR LEVEL') : 'YEAR LEVEL' }}
                                </small>

                            </div>
                        </div>

                    </div>
                    <div class="mt-4 row">
                        <div class="col-md">
                            <small class="text-muted">TRANSACT BY:</small>
                            <span class="badge bg-primary">{{ $_enrollment_status->staff->user->name }}</span>
                        </div>
                        <div class="col-md">
                            <small class="text-muted">TRANSACTION DATE:</small>
                            <span class="badge bg-primary">{{ $_enrollment_status->created_at->format('F d,Y') }}</span>
                        </div>
                    </div>
                </li>
                @if ($_enrollment_status->payment_assessments)
                    <li>
                        <div class="timeline-dots1 border-primary text-primary">
                            <svg width="20" viewBox="0 2 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.67 2H16.34C19.73 2 22 4.38 22 7.92V16.091C22 19.62 19.73 22 16.34 22H7.67C4.28 22 2 19.62 2 16.091V7.92C2 4.38 4.28 2 7.67 2ZM11.43 14.99L16.18 10.24C16.52 9.9 16.52 9.35 16.18 9C15.84 8.66 15.28 8.66 14.94 9L10.81 13.13L9.06 11.38C8.72 11.04 8.16 11.04 7.82 11.38C7.48 11.72 7.48 12.27 7.82 12.62L10.2 14.99C10.37 15.16 10.59 15.24 10.81 15.24C11.04 15.24 11.26 15.16 11.43 14.99Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                        <h5 class="float-left mb-1 text-primary fw-bolder">
                            TUITION FEE ASSESSMENT
                        </h5>
                        <div class="d-inline-block w-100">
                            <p>Successfully Transact</p>

                        </div>
                        <div class="row">
                            <div class="col-md">
                                <small class="text-muted">TRANSACT BY:</small>
                                <span
                                    class="badge bg-primary">{{ $_enrollment_status->payment_assessments->staff->user->name }}</span>
                            </div>
                            <div class="col-md">
                                <small class="text-muted">TRANSACTION DATE:</small>
                                <span
                                    class="badge bg-primary">{{ $_enrollment_status->payment_assessments->created_at->format('F d,Y') }}</span>
                            </div>
                        </div>
                    </li>
                    @if ($_enrollment_status->payment_assessments->payment_transaction)
                        <li>
                            <div class="timeline-dots1 border-primary text-primary">
                                <svg width="20" viewBox="0 2 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.67 2H16.34C19.73 2 22 4.38 22 7.92V16.091C22 19.62 19.73 22 16.34 22H7.67C4.28 22 2 19.62 2 16.091V7.92C2 4.38 4.28 2 7.67 2ZM11.43 14.99L16.18 10.24C16.52 9.9 16.52 9.35 16.18 9C15.84 8.66 15.28 8.66 14.94 9L10.81 13.13L9.06 11.38C8.72 11.04 8.16 11.04 7.82 11.38C7.48 11.72 7.48 12.27 7.82 12.62L10.2 14.99C10.37 15.16 10.59 15.24 10.81 15.24C11.04 15.24 11.26 15.16 11.43 14.99Z"
                                        fill="currentColor"></path>
                                </svg>
                            </div>
                            <h5 class="float-left mb-1 text-primary fw-bolder">
                                TUITION FEE PAYMENT
                            </h5>
                            <div class="d-inline-block w-100">
                                <p>Enrolled</p>

                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <small class="text-muted">TRANSACT BY:</small>
                                    <span
                                        class="badge bg-primary">{{ $_enrollment_status->payment_assessments->payment_assessment_paid->staff->user->name }}</span>
                                </div>
                                <div class="col-md">
                                    <small class="text-muted">TRANSACTION DATE:</small>
                                    <span
                                        class="badge bg-primary">{{ $_enrollment_status->payment_assessments->payment_assessment_paid->created_at->format('F d,Y') }}</span>
                                </div>
                            </div>
                        </li>
                    @else
                        @if ($_enrollment_status->payment_assessments->payment_transaction_online)
                        @else
                            <li>
                                <div class="timeline-dots timeline-dot1 border-secondary  text-success"></div>
                                <h5 class="float-left mb-1 text-muted fw-bolder">
                                    <i>TUITION FEE PAYMENT</i>
                                </h5>
                            </li>
                        @endif
                    @endif
                @else
                    <li>
                        <div class="timeline-dots1 border-info text-info">
                            <svg width="20" viewBox="0 2 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.67 2H16.34C19.73 2 22 4.38 22 7.92V16.09C22 19.62 19.73 22 16.34 22H7.67C4.28 22 2 19.62 2 16.09V7.92C2 4.38 4.28 2 7.67 2ZM7.52 13.2C6.86 13.2 6.32 12.66 6.32 12C6.32 11.34 6.86 10.801 7.52 10.801C8.18 10.801 8.72 11.34 8.72 12C8.72 12.66 8.18 13.2 7.52 13.2ZM10.8 12C10.8 12.66 11.34 13.2 12 13.2C12.66 13.2 13.2 12.66 13.2 12C13.2 11.34 12.66 10.801 12 10.801C11.34 10.801 10.8 11.34 10.8 12ZM15.28 12C15.28 12.66 15.82 13.2 16.48 13.2C17.14 13.2 17.67 12.66 17.67 12C17.67 11.34 17.14 10.801 16.48 10.801C15.82 10.801 15.28 11.34 15.28 12Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                        <h5 class="float-left mb-1 text-info fw-bolder">
                            TUITION FEE ASSESSMENT
                        </h5>
                        <div class="d-inline-block w-100">
                            <p>FOR TUITION FEE ASSESSMENT</p>
                        </div>
                    </li>
                    <li>
                        <div class="timeline-dots timeline-dot1 border-secondary  text-success"></div>
                        <h5 class="float-left mb-1 text-muted fw-bolder">
                            <i>TUITION FEE PAYMENT</i>
                        </h5>
                    </li>
                @endif
            @else
                <li>
                    <div class="timeline-dots timeline-dot1 border-secondary  text-success"></div>
                    <h5 class="float-left mb-1 text-muted fw-bolder">
                        <i>ENROLLMENT ASSESSMENT</i>
                    </h5>
                </li>
                <li>
                    <div class="timeline-dots timeline-dot1 border-secondary  text-success"></div>
                    <h5 class="float-left mb-1 text-muted fw-bolder">
                        <i>TUITION FEE ASSESSMENT</i>
                    </h5>
                </li>
                <li>
                    <div class="timeline-dots timeline-dot1 border-secondary  text-success"></div>
                    <h5 class="float-left mb-1 text-muted fw-bolder">
                        <i>TUITION FEE PAYMENT</i>
                    </h5>
                </li>
            @endif
        @else
            <li>
                <div class="timeline-dots timeline-dot1 border-secondary  text-success"></div>
                <h5 class="float-left mb-1 text-muted fw-bolder">
                    <i>ENROLLMENT APPLICATION</i>
                </h5>
            </li>
            <li>
                <div class="timeline-dots timeline-dot1 border-secondary  text-success"></div>
                <h5 class="float-left mb-1 text-muted fw-bolder">
                    <i>ENROLLMENT ASSESSMENT</i>
                </h5>
            </li>
            <li>
                <div class="timeline-dots timeline-dot1 border-secondary  text-success"></div>
                <h5 class="float-left mb-1 text-muted fw-bolder">
                    <i>TUITION FEE ASSESSMENT</i>
                </h5>
            </li>
            <li>
                <div class="timeline-dots timeline-dot1 border-secondary  text-success"></div>
                <h5 class="float-left mb-1 text-muted fw-bolder">
                    <i>TUITION FEE PAYMENT</i>
                </h5>
            </li>
        @endif

        {{-- Enrollment Assessment --}}
        {{-- Tuition Fee Assessments --}}
        {{-- Tuition Fee Payment --}}
    </ul>
@endsection
