@php
$_url_card = route('applicant-profile') . '?' . (request()->input('_academic') ? '_academic=' . request()->input('_academic') . '&' : '') . '_course=' . request()->input('_course') . '&view=' . request()->input('view');
@endphp
@if (request()->input('view') == 'pre-registration')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class="text-primary fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md">
                            <small>APPLICATION DATE</small>
                            <div class="badge bg-primary w-100">

                                <span>{{ $_data->created_at->format('F d, Y') }}</span>
                            </div>

                            <a href="{{ route('applicant-removed') }}?_applicant={{ base64_encode($_data->id) }}"
                                class="badge bg-danger text-white w-100">REMOVE
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @if ($_data->applicant_documents->count() > 0)
                        <div class="badge bg-info">DOCUMENTS- </div>
                        @if ($_data->applicant_documents_status())
                            <div class="badge bg-info">VERIFIED-</div>
                            @if ($_data->payment)
                                <div class="badge bg-info">PAYMENT-</div>
                                @if ($_data->payment->is_approved == true)
                                    <div class="badge bg-info">APPROVED-</div>
                                @else
                                    <div class="badge bg-secondary">NOT-APPROVED </div>
                                    {{ $_data->payment->is_approved }}
                                @endif
                            @else
                                <div class="badge bg-secondary">NO-PAYMENT </div>
                            @endif
                        @else
                            <div class="badge bg-secondary">NOT-VERIFIED </div>
                        @endif
                        {{ $_data->applicant_document_status }}
                    @else
                        <div class="badge bg-secondary">NO-DOCUMENTS</div>
                    @endif
                </div>
            </div>
        @endforeach
    @endsection
    {{-- <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <span><b>{{ $_data->applicant ? $_data->applicant_number : '-' }}</b></span>
                    <a href="{{ $_profile_link }}?_student={{ base64_encode($_data->id) }}">
                        <div class="mt-2">
                            <h2 class="counter" style="visibility: visible;">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </h2>
                        </div>
                    </a>
                    <span class="badge bg-primary">{{ $_data->course->course_name }}</span> -
                    <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                    <span class="badge bg-black">
                        @php
                            echo $_data->applicant->check_duplicate();
                        @endphp
                    </span>
                </div>
                <div class="col-md">
                    <div class="badge bg-primary w-100">
                        <span>{{ $_data->created_at->format('F d, Y') }}</span>
                    </div>

                    <a href="{{ route('applicant-removed') }}?_applicant={{ base64_encode($_data->id) }}"
                        class="badge bg-danger text-white w-100">REMOVE
                    </a>
                </div>
            </div>
        </div>
    </div> --}}
@endif

@if (request()->input('view') == 'for-checking')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class="text-primary fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md">
                            <small>APPLICATION DATE</small>
                            <div class="badge bg-primary w-100">

                                <span>{{ $_data->created_at->format('F d, Y') }}</span>
                            </div>

                            <a href="{{ route('applicant-removed') }}?_applicant={{ base64_encode($_data->id) }}"
                                class="badge bg-danger text-white w-100">REMOVE
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endsection
@endif

@if (request()->input('view') == 'verified')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md">
                            <small>APPLICATION DATE</small>
                            <div class="badge bg-primary w-100">

                                <span>{{ $_data->created_at->format('F d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endsection
@endif


@if (request()->input('view') == 'entrance-examination-payment-verification')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md">
                            <small>PAYMENT DATE</small>
                            <div class="badge bg-primary w-100">

                                <span>{{ $_data->payment->created_at->format('F d Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    Documents File: {{ $_data->applicant_documents->count() }}
                </div>
            </div>
        @endforeach
    @endsection
@endif

@if (request()->input('view') == 'entrance-examination-payment-verified')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md">
                            <small>APPROVED PAYMENT DATE</small>
                            <div class="badge bg-primary w-100">

                                <span>{{ $_data->payment->updated_at->format('F d Y') }}</span>
                            </div>
                            {{-- NOTIFICATION BUTTON TO STUDENT --}}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endsection
@endif


@if (request()->input('view') == 'ongoing-examination')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md">
                            @if ($_data->applicant_examination)
                                @if ($_data->applicant_examination->is_finish === 0)
                                    <small
                                        class="badge bg-info">{{ $_data->applicant_examination->updated_at->format('F d,Y') }}</small>
                                    <br>
                                    <small class="text-muted fw-bolder mb-0">Timing Start</small>
                                    <h3 class="text-primary fw-bolder ">
                                        {{ $_data->applicant_examination->updated_at->format('h:m:s') }}</h3>
                                @endif

                                @if ($_data->applicant_examination->is_finish === 1)
                                    <small
                                        class="badge bg-info">{{ $_data->applicant_examination->updated_at->format('F d, Y') }}</small>
                                    <br>
                                    <div class="row">
                                        <div class="col-md">
                                            <small class="fw-bolder">SCORE</small>
                                            <h3 class="text-primary fw-bolder mt-3">

                                                {{ $_data->applicant_examination->examination_result()[0] }}</h3>
                                        </div>
                                        <div class="col-md">
                                            <small class="fw-bolder">PERCENTILE</small>
                                            <h3 class="text-primary fw-bolder mt-3">

                                                {{ $_data->applicant_examination->examination_result()[1] }}</h3>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            {{-- NOTIFICATION BUTTON TO STUDENT --}}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endsection
@endif


@if (request()->input('view') == 'examination-passed')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md ps-0">
                            <small> EXAMINATION DATE</small>
                            <small
                                class="badge bg-info">{{ $_data->applicant_examination->updated_at->format('F d, Y') }}</small>
                            <br>
                            <div class="row">
                                <div class="col-md">
                                    <small class="fw-bolder">SCORE</small>
                                    <h4 class="text-primary fw-bolder mt-3">

                                        {{ $_data->applicant_examination->examination_result()[0] }}</h4>
                                </div>
                                <div class="col-md">
                                    <small class="fw-bolder">PERCENTILE</small>
                                    <h4 class="text-primary fw-bolder mt-3">

                                        {{ $_data->applicant_examination->examination_result()[1] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    Documents File: {{ $_data->applicant_documents->count() }}
                </div>
            </div>
        @endforeach
    @endsection
@endif

@if (request()->input('view') == 'examination-failed')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md ps-0">
                            <small> EXAMINATION DATE</small>
                            <small
                                class="badge bg-info">{{ $_data->applicant_examination->updated_at->format('F d, Y') }}</small>
                            <br>
                            <div class="row">
                                <div class="col-md">
                                    <small class="fw-bolder">SCORE</small>
                                    <h4 class="text-primary fw-bolder mt-3">

                                        {{ $_data->applicant_examination->examination_result()[0] }}</h4>
                                </div>
                                <div class="col-md">
                                    <small class="fw-bolder">PERCENTILE</small>
                                    <h4 class="text-primary fw-bolder mt-3">

                                        {{ $_data->applicant_examination->examination_result()[1] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    Documents File: {{ $_data->applicant_documents->count() }}
                    <a href="{{ route('applicant-examination-reset') }}?_applicant={{ base64_encode($_data->id) }}"
                        class="btn btn-sm btn-primary float-end">Reset Examination</a>
                </div>
            </div>
        @endforeach
    @endsection
@endif

@if (request()->input('view') == 'virtual-orientation')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md ps-0">
                            <small>ORIENTATION DATE</small>
                            <small
                                class="badge bg-info">{{ $_data->virtual_orientation->updated_at->format('F d, Y') }}</small>
                            <br>

                        </div>
                    </div>
                </div>
                {{-- <div class="card-footer">
                    Documents File: {{ $_data->applicant_documents->count() }}
                    <a href="{{ route('applicant-examination-reset') }}?_applicant={{ base64_encode($_data->id) }}"
                        class="btn btn-sm btn-primary float-end">Reset Examination</a>
                </div> --}}
            </div>
        @endforeach
    @endsection
@endif

@if (request()->input('view') == 'medical-appointment')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md ps-0">
                            {{-- <small>ORIENTATION DATE</small>
                            <small
                                class="badge bg-info">{{ $_data->applicant_examination->updated_at->format('F d, Y') }}</small>
                            <br> --}}

                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    @endsection
@endif

@if (request()->input('view') == 'medical-scheduled')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md ps-0">
                            <small>APPOINTMENT DATE</small>
                            <small
                                class="badge bg-info">{{ $_data->medical_appointment->updated_at->format('F d, Y') }}</small>
                            <br>

                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    @endsection
@endif
@if (request()->input('view') == 'medical-results')
    @section('applicant-card')
        @foreach ($_applicants as $_data)
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="fw-bolder text-muted mb-0">
                                <span class="badge bg-primary">{{ $_data->course->course_name }}</span> |
                                {{ $_data->applicant ? $_data->applicant_number : '-' }}
                            </p>
                            <a href="{{ $_url_card }}&_applicant={{ base64_encode($_data->id) }}"
                                class=" fw-bolder h2">
                                {{ strtoupper($_data->applicant->last_name . ', ' . $_data->applicant->first_name) }}
                            </a>


                            <div class="mt-0">
                                <span>{{ $_data->applicant ? $_data->email : '-' }}</span> <br>
                                <span class="badge bg-secondary">
                                    @php
                                        echo $_data->applicant->check_duplicate();
                                    @endphp
                                </span>
                            </div>


                        </div>
                        <div class="col-md ps-0">
                            <small>MEDICAL RESULT</small>
                            @if ($_data->medical_result->is_fit === 1)
                                <small class="badge bg-info">FIT TO ENROLL</small>
                            @else
                                @if ($_data->medical_result->is_fit === 2)
                                    <small class="badge bg-danger">NOT FIT TO ENROLL</small>
                                @else
                                    <small class="badge bg-secondary">PENDING</small> <br>
                                    <small>{{ $_data->medical_result->remarks }}</small>
                                @endif
                            @endif

                            <br>

                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    @endsection
@endif
